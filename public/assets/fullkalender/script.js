$(document).ready(function(){
    var kalender = $('#kalender').fullkalender({
        editable: true,
        selectable: true,
        selecthelper: true,
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
        },
        buttonText: {
            today: 'today',
            month: 'month',
            week: 'week',
            day: 'day',
        },
        events: 'history.blade.php'
    })
})