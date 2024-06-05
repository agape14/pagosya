(function($) {
    "use strict"

    //date picker classic default
    $('.datepicker-default').pickadate({
        monthsFull: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        monthsShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
        weekdaysFull: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
        weekdaysShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'],
        today: 'Hoy',
        clear: 'Borrar',
        close: 'Cerrar',
        firstDay: 1,
        format: 'dd/mm/yyyy',
        formatSubmit: 'yyyy-mm-dd',
        closeOnSelect: true // Close upon selecting a date
    });

})(jQuery);