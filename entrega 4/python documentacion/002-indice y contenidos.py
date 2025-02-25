import os
import tkinter as tk
from tkinter import filedialog, ttk

# Configuración de colores y estilos modernos
theme_bg = "#121212"
theme_fg = "#E0E0E0"
theme_btn_bg = "#1F1F1F"
theme_btn_fg = "#BB86FC"
theme_tree_bg = "#1E1E1E"
theme_tree_fg = "#E0E0E0"
theme_tree_highlight = "#BB86FC"
theme_border = "#333333"
font_main = ("Arial", 12)
font_title = ("Arial", 16, "bold")

def listar_estructura_markdown(ruta, tree, parent=""):
    """
    Genera la estructura del directorio y la muestra en un Treeview.
    """
    for item in sorted(os.listdir(ruta)):
        item_path = os.path.join(ruta, item)
        is_dir = os.path.isdir(item_path)
        node = tree.insert(parent, "end", text=item, open=False)
        
        if is_dir:
            listar_estructura_markdown(item_path, tree, node)

def seleccionar_carpeta():
    """Abre un diálogo para seleccionar una carpeta y muestra su estructura."""
    carpeta = filedialog.askdirectory()
    if carpeta:
        tree.delete(*tree.get_children())  # Limpiar árbol anterior
        listar_estructura_markdown(carpeta, tree)

# Configuración de la ventana principal
root = tk.Tk()
root.title("Explorador de Estructura de Proyecto")
root.geometry("800x600")
root.configure(bg=theme_bg)
root.resizable(False, False)

# Título estilizado
title_label = tk.Label(root, text="Explorador de Proyecto", font=font_title, fg=theme_fg, bg=theme_bg)
title_label.pack(pady=15)

# Botón para seleccionar carpeta
btn_seleccionar = tk.Button(root, text="Seleccionar Carpeta", command=seleccionar_carpeta,
                            bg=theme_btn_bg, fg=theme_btn_fg, font=font_main, padx=15, pady=8, border=0,
                            activebackground=theme_tree_highlight, activeforeground=theme_fg, cursor="hand2")
btn_seleccionar.pack(pady=10)

# Frame para el Treeview con bordes
frame = tk.Frame(root, bg=theme_border, padx=2, pady=2)
frame.pack(expand=True, fill="both", padx=20, pady=10)

# Estilizar el Treeview
style = ttk.Style()
style.configure("Treeview", background=theme_tree_bg, foreground=theme_tree_fg, fieldbackground=theme_tree_bg,
                borderwidth=0, rowheight=25, font=font_main)
style.map("Treeview", background=[("selected", theme_tree_highlight)])
style.configure("Treeview.Heading", font=("Arial", 13, "bold"), background=theme_tree_bg, foreground=theme_fg)

# Scrollbar personalizada
scrollbar = ttk.Scrollbar(frame, orient="vertical")
tree = ttk.Treeview(frame, yscrollcommand=scrollbar.set)
scrollbar.config(command=tree.yview)
scrollbar.pack(side="right", fill="y")

# Widget Treeview para mostrar la estructura de carpetas
tree.pack(expand=True, fill="both")

# Iniciar la aplicación
tk.mainloop()

