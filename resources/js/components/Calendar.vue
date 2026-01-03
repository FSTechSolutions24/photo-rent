<template>
  <FullCalendar :options="calendarOptions" />
</template>

<script setup>
import FullCalendar from '@fullcalendar/vue3';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';

const calendarOptions = {
    plugins: [dayGridPlugin, timeGridPlugin],
    initialView: 'dayGridMonth',
    headerToolbar: {
    left: 'prev,next today',
    center: 'title',
    right: 'dayGridMonth,timeGridWeek,timeGridDay'
    },
    events: [
        {
            title: 'Vue Event',
            start: '2026-01-05',
            backgroundColor: '#28a745', // green
            borderColor: '#28a745',
            textColor: '#ffffff'
        },
        {
            title: 'AdminLTE Meeting',
            start: '2026-01-10',
            backgroundColor: '#007bff', // blue
            borderColor: '#007bff',
            textColor: '#ffffff'
        }
    ]

};
</script>



