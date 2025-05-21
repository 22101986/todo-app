@extends('layouts.app')

@section('content')
<div class="container">
    <div id='calendar'></div>
</div>
@endsection

@push('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
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
                        start: arg.startStr,
                        end: arg.endStr,
                        all_day: arg.allDay
                    })
                    .then(response => {
                        calendar.refetchEvents();
                        alert('Événement créé avec succès !');
                    })
                    .catch(error => {
                        let msg = "Erreur lors de la création de l'événement.";
                        if (error.response && error.response.data && error.response.data.message) {
                            msg += "\n" + error.response.data.message;
                        }
                        alert(msg);
                        console.error(error);
                    });
                }
                calendar.unselect();
            },
            eventClick: function(arg) {
                if (confirm('Delete this event?')) {
                    axios.delete(`/calendar/${arg.event.id}`)
                        .then(() => {
                            arg.event.remove();
                            alert('Événement supprimé !');
                        })
                        .catch(error => {
                            let msg = "Erreur lors de la suppression de l'événement.";
                            if (error.response && error.response.data && error.response.data.message) {
                                msg += "\n" + error.response.data.message;
                            }
                            alert(msg);
                            console.error(error);
                        });
                }
            },
            eventDrop: function(arg) {
                axios.put(`/calendar/${arg.event.id}`, {
                    start: arg.event.startStr,
                    end: arg.event.endStr,
                    all_day: arg.event.allDay
                })
                .then(() => {
                    alert('Événement déplacé !');
                })
                .catch(error => {
                    let msg = "Erreur lors du déplacement de l'événement.";
                    if (error.response && error.response.data && error.response.data.message) {
                        msg += "\n" + error.response.data.message;
                    }
                    alert(msg);
                    arg.revert();
                    console.error(error);
                });
            },
            eventResize: function(arg) {
                axios.put(`/calendar/${arg.event.id}`, {
                    start: arg.event.startStr,
                    end: arg.event.endStr
                })
                .then(() => {
                    alert('Événement redimensionné !');
                })
                .catch(error => {
                    let msg = "Erreur lors du redimensionnement de l'événement.";
                    if (error.response && error.response.data && error.response.data.message) {
                        msg += "\n" + error.response.data.message;
                    }
                    alert(msg);
                    arg.revert();
                    console.error(error);
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