import tkinter as tk
from tkinter import ttk, messagebox
import subprocess
import json

def execute_db_command(command_args):
    """Ejecuta un comando en la base de datos y devuelve el resultado."""
    try:
        resultado = subprocess.run(command_args, capture_output=True, text=True)
        return resultado.returncode, resultado.stdout, resultado.stderr
    except Exception as e:
        return -1, "", str(e)

def insert_data():
    nombre = name_entry.get().strip()
    edad = age_entry.get().strip()
    
    if not nombre or not edad.isdigit():
        messagebox.showerror("Error", "Por favor, ingrese un nombre y una edad válida.")
        return
    
    json_datos = json.dumps({"nombre": nombre, "edad": int(edad)})
    comando_insertar = ["mydbapp.exe", "clientes", "insert", json_datos]
    codigo_retorno, salida, error = execute_db_command(comando_insertar)
    
    if codigo_retorno == 0:
        messagebox.showinfo("Éxito", f"Datos insertados: {json_datos}")
    else:
        messagebox.showerror("Error al insertar", error)

def retrieve_data():
    comando_seleccionar = ["mydbapp.exe", "clientes", "select"]
    codigo_retorno, salida, error = execute_db_command(comando_seleccionar)
    
    if codigo_retorno == 0:
        results_text.config(state=tk.NORMAL)
        results_text.delete("1.0", tk.END)
        results_text.insert(tk.END, salida)
        results_text.config(state=tk.DISABLED)
    else:
        messagebox.showerror("Error al recuperar datos", error)

def update_data():
    file_name = file_entry.get().strip()
    nombre = name_entry.get().strip()
    edad = age_entry.get().strip()
    
    if not file_name or not nombre or not edad.isdigit():
        messagebox.showerror("Error", "Ingrese un archivo, nombre y edad válida para actualizar.")
        return
    
    nuevo_json = json.dumps({"nombre": nombre, "edad": int(edad)})
    comando_actualizar = ["mydbapp.exe", "clientes", "update", file_name, nuevo_json]
    codigo_retorno, salida, error = execute_db_command(comando_actualizar)
    
    if codigo_retorno == 0:
        messagebox.showinfo("Éxito", f"Registro actualizado: {nuevo_json}")
    else:
        messagebox.showerror("Error al actualizar", error)

# Configuración de la ventana
root = tk.Tk()
root.title("Gestor de Base de Datos JSON")
root.geometry("500x400")
root.configure(bg="#f4f4f4")

style = ttk.Style()
style.configure("TButton", font=("Arial", 12), padding=5)
style.configure("TLabel", font=("Arial", 11))
style.configure("TEntry", font=("Arial", 11), padding=5)

frame = ttk.Frame(root, padding=15)
frame.pack(expand=True)

# Entradas y etiquetas
ttk.Label(frame, text="Archivo:").grid(row=0, column=0, sticky="w", pady=5)
file_entry = ttk.Entry(frame, width=30)
file_entry.grid(row=0, column=1, pady=5)

ttk.Label(frame, text="Nombre:").grid(row=1, column=0, sticky="w", pady=5)
name_entry = ttk.Entry(frame, width=30)
name_entry.grid(row=1, column=1, pady=5)

ttk.Label(frame, text="Edad:").grid(row=2, column=0, sticky="w", pady=5)
age_entry = ttk.Entry(frame, width=30)
age_entry.grid(row=2, column=1, pady=5)

# Botones
button_frame = ttk.Frame(frame)
button_frame.grid(row=3, column=0, columnspan=2, pady=10)

ttk.Button(button_frame, text="Insertar", command=insert_data).grid(row=0, column=0, padx=5)
ttk.Button(button_frame, text="Listar", command=retrieve_data).grid(row=0, column=1, padx=5)
ttk.Button(button_frame, text="Actualizar", command=update_data).grid(row=0, column=2, padx=5)

# Área de resultados
text_frame = ttk.Frame(frame)
text_frame.grid(row=4, column=0, columnspan=2, pady=10)

results_text = tk.Text(text_frame, width=60, height=10, state=tk.DISABLED, bg="#ffffff", font=("Arial", 10))
scrollbar = ttk.Scrollbar(text_frame, command=results_text.yview)
results_text.config(yscrollcommand=scrollbar.set)
results_text.grid(row=0, column=0)
scrollbar.grid(row=0, column=1, sticky="ns")

root.mainloop()

