import mysql.connector

class Persona:
    def __init__(self,
                    nuevonombre,
                    nuevosapellidos,
                    nuevaedad,nuevosemails):
        self.nombre = nuevonombre
        self.apellidos = nuevosapellidos
        self.edad = nuevaedad
        self.emails = nuevosemails

personas = []
personas.append(Persona("Jose Vicente","Carratala Sanchis",46,['info@jocarsa.com','info@josevicentecarratala.com']))
personas.append(Persona("Juan","Garcia",50,['juan@garcia.com','garcia@garcia.com']))


conexion = mysql.connector.connect(
            host='localhost',  
            database='accesoadatos', 
            user='luiss',  
            password='luis'  
        )
cursor = conexion.cursor()
for persona in personas:
    peticion = f"""
                INSERT INTO personas VALUES (NULL,'{persona.nombre}','{persona.apellidos}',{persona.edad});
                """
    cursor.execute(peticion)
conexion.commit()
