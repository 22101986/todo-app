@extends('layouts.app')

@section('content')
<div class="container">
    <div id='calendar'></div>
</div>
@endsection

@push('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('calendar');
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            events: '{{ route("calendar.events") }}',
            editable: true,
            selectable: true,
            selectMirror: true,
            select: function(arg) {
                const title = prompt('Event Title:');
                if (title) {
                    axios.post('{{ route("calendar.store") }}', {
                        title: title,
                        start: arg.start,
                        end: arg.end,
                        all_day: arg.allDay
                    }).then(response => {
                        calendar.refetchEvents();
                    });
                }
                calendar.unselect();
            },
            eventClick: function(arg) {
                if (confirm('Delete this event?')) {
                    axios.delete(`/calendar/${arg.event.id}`)
                        .then(() => {
                            arg.event.remove();
                        });
                }
            },
            eventDrop: function(arg) {
                axios.put(`/calendar/${arg.event.id}`, {
                    start: arg.event.start,
                    end: arg.event.end,
                    all_day: arg.event.allDay
                });
            },
            eventResize: function(arg) {
                axios.put(`/calendar/${arg.event.id}`, {
                    start: arg.event.start,
                    end: arg.event.end
                });
            }
        });
        calendar.render();
    });
</script>
@endpush

@push('styles')
<style>
    #calendar {
        max-width: 1100px;
        margin: 40px auto;
    }
</style>
@endpush