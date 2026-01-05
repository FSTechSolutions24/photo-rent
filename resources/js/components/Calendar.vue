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
    contentHeight: 700,
    headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,timeGridDay'
    },
    eventClick: function(info) {
        // prevent FullCalendar default behavior
        info.jsEvent.preventDefault()

        // example: open appointment details page
        const appointmentId = info.event.id
        window.location.href = `/photographer/appointments/${appointmentId}/edit`
    },
    eventMouseEnter(info) {
        info.el.style.cursor = 'pointer'
    },
    events: async (info, successCallback, failureCallback) => {
        try {
            const response = await axios.get('/photographer/appointments/data')
            successCallback(
                response.data.map(event => ({
                    id: event.id,
                    title: event.name,
                    start: event.date,
                    backgroundColor: '#073b74',
                    borderColor: '#073b74',
                    textColor: '#ffffff'
                }))
            )
        } catch (error) {
            failureCallback(error)
        }
    }

};
</script>



