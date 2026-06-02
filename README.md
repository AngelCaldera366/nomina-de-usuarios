# 💰 Sistema de Gestión y Consulta de Nómina en PHP

Este es un sistema web básico y funcional desarrollado en **PHP nativo** y **MySQL** para la gestión y cálculo de nóminas de empleados. Permite registrar nuevos trabajadores con contraseñas encriptadas, calcular salarios basados en incidencias mensuales (como horas extras), aplicar deducciones de ley y ofrecer un portal privado para que cada empleado consulte su historial de pagos de forma segura.

## 🚀 Características del Proyecto
* 🔐 **Autenticación Segura:** Sistema de registro e inicio de sesión con manejo de sesiones nativas de PHP (`$_SESSION`).
* 🔒 **Seguridad de Datos:** Contraseñas encriptadas en la base de datos utilizando el algoritmo de hash `PASSWORD_DEFAULT`.
* 🛡️ **Protección de Rutas:** Consultas optimizadas con sentencias preparadas (**PDO**) para mitigar ataques de inyección SQL (SQLi).
* 📊 **Cálculo Automatizado:** Procesamiento de sueldos brutos, deducciones legales de salud/pensión (4%) y determinación del salario neto líquido.
* 👥 **Portal del Empleado:** Panel privado donde el usuario visualiza únicamente sus desprendibles de pago ordenados por periodo cronológico.

---

## 📂 Estructura del Directorio

El proyecto mantiene una arquitectura limpia separada por responsabilidades y roles:

```text
sistema-nomina/
│
├── config/
│   └── conexion.php          # Conexión centralizada mediante PDO
│
├── auth/
│   ├── login.html            # Formulario visual de acceso para empleados
│   ├── login_procesar.php    # Validación de credenciales y password_verify()
│   ├── registro.html         # Formulario visual de alta de personal
│   ├── registrar_procesar.php# Procesamiento e inserción de datos encriptados
│   └── logout.php            # Cierre seguro de la sesión activa
│
├── admin/
│   ├── calcular_nomina.php   # Interfaz para capturar incidencias (Horas extras)
│   └── procesar_nomina.php   # Lógica matemática e inserción en el histórico
│
├── empleado/
│   └── mi_nomina.php         # Panel privado de consulta del empleado
│
└── index.php                 # Enrutador raíz principal hacia el login
```

---

## 🛠️ Requisitos del Sistema
Para correr este proyecto localmente necesitas un entorno de desarrollo PHP local como:
* [XAMPP](https://apachefriends.org) / WampServer / MAMP
* PHP 8.0 o superior
* Motor de Base de Datos MySQL / MariaDB
* Extensión PDO habilitada en PHP

---

## ⚙️ Instalación y Configuración

Sigue estos pasos para levantar el entorno de manera local:

### 1. Clonar el repositorio
Mueve la terminal a la carpeta de despliegue de tu servidor local (`htdocs` o `www`) y ejecuta:
```bash
git clone https://github.com
cd sistema-nomina
```

### 2. Importar el Modelo de Datos
Abre tu gestor de base de datos preferido (como **phpMyAdmin**) y ejecuta el siguiente script SQL para crear la estructura relacional:

```sql
CREATE DATABASE sistema_nomina;
USE sistema_nomina;

-- 1. Tabla de empleados con credenciales de acceso
CREATE TABLE empleados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    cedula VARCHAR(20) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    salario_base DECIMAL(10,2) NOT NULL,
    password VARCHAR(255) NOT NULL
);

-- 2. Tabla de histórico de nóminas liquidadas
CREATE TABLE nominas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    empleado_id INT,
    periodo VARCHAR(7), -- Formato: 'YYYY-MM'
    horas_extras INT DEFAULT 0,
    total_devengado DECIMAL(10,2),
    deduccion_salud DECIMAL(10,2),
    deduccion_pension DECIMAL(10,2),
    salario_neto DECIMAL(10,2),
    FOREIGN KEY (empleado_id) REFERENCES empleados(id) ON DELETE CASCADE
);
```

### 3. Configurar Parámetros de Conexión
Abre el archivo `config/conexion.php` y edita los valores correspondientes a los accesos de tu servidor local de base de datos si es necesario:
```php
\$host = 'localhost';
\(db   = 'sistema_nomina';\)user = 'root'; // Ajustar según tu servidor
\$pass = '';     // Ajustar según tu servidor
```

---

## 📖 Instrucciones de Uso

1. **Paso 1 (Registro):** Entra a `http://localhost/sistema-nomina/auth/registro.html` y da de alta a los empleados con su sueldo base y datos personales.
2. **Paso 2 (Cálculo):** Utiliza la ruta del administrador `http://localhost/sistema-nomina/admin/calcular_nomina.php` para ingresar el ID de un empleado, el periodo del mes y cargar las horas extras ejecutadas. El backend procesará los cálculos y registrará el pago.
3. **Paso 3 (Portal):** Los empleados pueden ingresar desde la raíz del sistema `http://localhost/sistema-nomina/`. Tras autenticarse exitosamente, serán redirigidos a `empleado/mi_nomina.php` donde únicamente podrán consultar su información financiera privada.

---

## 📝 Licencia
Este proyecto es de código abierto y está disponible bajo la Licencia MIT.
