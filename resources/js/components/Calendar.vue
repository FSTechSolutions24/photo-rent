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

        const editUrl = info.event.extendedProps.editUrl
        if (editUrl) {
            window.location.href = editUrl
        }
    },
    eventMouseEnter(info) {
        info.el.style.cursor = 'pointer'
    },
    events: async (info, successCallback, failureCallback) => {
        try {
            const response = await axios.get('/photographer/appointments/data')
            successCallback(response.data)
        } catch (error) {
            failureCallback(error)
        }
    }

};
</script>



