//ejercico  649 mostrar la fecha y hora en el sigueinte formato dia HH AM/PM :MM : SS.



let hoy = new Date();
let dias = ['domingo', 'lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado'];
let numerodiasemana = hoy.getDay();

console.log(`Hoy es: ${dias[numerodiasemana]}`);
console.log();

let horas = hoy.getHours();
let minutos = hoy.getMinutes();
let segundos = hoy.getSeconds();

let jornada = horas >= 12 ? 'PM' : 'AM';
let horas12 = horas % 12 || 12;

minutos = ('0'+ minutos).slice(-2);
segundos = ('0'+ segundos).slice(-2);



console.log(`${dias[numerodiasemana]} ${horas12} ${jornada} :${minutos} :${segundos}`);