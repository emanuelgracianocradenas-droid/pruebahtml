// db.js
const mysql = require('mysql2/promise');
// Configuración de la conexión a la base de datos
const pool = mysql.createPool({
  host: 'localhost',
  port: 3306,
  user: 'root',
  password: '', // por defecto, la contraseña es una cadena vacía
  database: 'pruebahtml',
  waitForConnections: true,
  connectionLimit: 10,
  queueLimit: 0
});

module.exports = pool;