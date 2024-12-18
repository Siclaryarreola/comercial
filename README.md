# Proyecto Comercial

Este proyecto es una plataforma comercial desarrollada en PHP con el patrón MVC. Permite gestionar leads, reportes gráficos y otros componentes clave para una tienda comercial.

## Características
- Arquitectura **MVC** para un mejor mantenimiento.
- Base de datos **MySQL**.
- Reportes gráficos con **Chart.js**.
- Interfaz responsiva.

## Requisitos Previos
- **XAMPP o WAMP**.
- **PHP 8** o superior.
- **MySQL**.

## Instalación
1. Clona el repositorio:
   ```bash
   git clone https://github.com/Siclaryarreola/comercial.git

   
## Base de Datos

Para que el proyecto funcione correctamente, debes importar la base de datos:

1. Abre **phpMyAdmin** o tu cliente MySQL preferido.
2. Crea una base de datos llamada `intran23_comercial`.
3. Importa el archivo **comercial.sql** incluido en la raíz del proyecto.

### Configuración de la Conexión a la Base de Datos

Asegúrate de configurar correctamente el acceso a la base de datos en el archivo:


Ejemplo de configuración:

```php
$host = "localhost";
$user = "root";
$password = ""; // Por defecto en XAMPP
$dbname = "intran23_comercial";
