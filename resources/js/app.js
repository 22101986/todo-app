require('./bootstrap');

// Import des composants FullCalendar
import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import interactionPlugin from '@fullcalendar/interaction';
import listPlugin from '@fullcalendar/list';
import axios from 'axios';

// Initialisation du calendrier
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    
    if (calendarEl) {
        const calendar = new Calendar(calendarEl, {
            plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin, listPlugin],
            
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
            },
            
            initialView: 'dayGridMonth',
            firstDay: 1,
            navLinks: true,
            nowIndicator: true,
            editable: true,
            selectable: true,
            dayMaxEvents: true,
            locale: 'fr',
            buttonText: {
                today: 'Aujourd\'hui',
                month: 'Mois',
                week: 'Semaine',
                day: 'Jour',
                list: 'Liste'
            },
            
            events: {
                url: '/calendar/events',
                method: 'GET',
                failure: function() {
                    alert('Erreur lors du chargement des événements!');
                }
            },

            select: function(info) {
                const title = prompt('Titre de l\'événement:');
                if (title) {
                    axios.post('/calendar', {
                        title: title,
                        start: info.startStr,
                        end: info.endStr,
                        all_day: info.allDay
                    })
                    .then(response => {
                        calendar.refetchEvents();
                        alert('Événement créé avec succès!');
                    })
                    .catch(error => {
                        let msg = "Erreur lors de la création!";
                        if (error.response && error.response.data && error.response.data.message) {
                            msg += "\n" + error.response.data.message;
                        }
                        alert(msg);
                        console.error(error);
                    });
                }
                calendar.unselect();
            },

            eventClick: function(info) {
                const action = prompt(`Événement: ${info.event.title}\n\nQue souhaitez-vous faire? (supprimer/modifier)`);
                
                if (action === 'supprimer') {
                    if (confirm('Confirmez la suppression de "' + info.event.title + '"?')) {
                        axios.delete(`/calendar/${info.event.id}`)
                            .then(() => {
                                info.event.remove();
                                alert('Événement supprimé!');
                            })
                            .catch(error => {
                                let msg = "Erreur lors de la suppression!";
                                if (error.response && error.response.data && error.response.data.message) {
                                    msg += "\n" + error.response.data.message;
                                }
                                alert(msg);
                                console.error(error);
                            });
                    }
                } else if (action === 'modifier') {
                    const newTitle = prompt('Nouveau titre:', info.event.title);
                    if (newTitle !== null) {
                        axios.put(`/calendar/${info.event.id}`, {
                            title: newTitle
                        })
                        .then(response => {
                            info.event.setProp('title', newTitle);
                            alert('Événement mis à jour!');
                        })
                        .catch(error => {
                            let msg = "Erreur lors de la modification!";
                            if (error.response && error.response.data && error.response.data.message) {
                                msg += "\n" + error.response.data.message;
                            }
                            alert(msg);
                            console.error(error);
                        });
                    }
                }
            },

            eventDrop: function(info) {
                axios.put(`/calendar/${info.event.id}`, {
                    start: info.event.startStr,
                    end: info.event.endStr,
                    all_day: info.event.allDay
                })
                .then(() => {
                    alert('Événement déplacé!');
                })
                .catch(error => {
                    let msg = "Erreur lors du déplacement!";
                    if (error.response && error.response.data && error.response.data.message) {
                        msg += "\n" + error.response.data.message;
                    }
                    alert(msg);
                    info.revert();
                    console.error(error);
                });
            },

            eventResize: function(info) {
                axios.put(`/calendar/${info.event.id}`, {
                    start: info.event.startStr,
                    end: info.event.endStr
                })
                .then(() => {
                    alert('Événement redimensionné!');
                })
                .catch(error => {
                    let msg = "Erreur lors du redimensionnement!";
                    if (error.response && error.response.data && error.response.data.message) {
                        msg += "\n" + error.response.data.message;
                    }
                    alert(msg);
                    info.revert();
                    console.error(error);
                });
            },

            eventContent: function(arg) {
                return {
                    html: `<div class="fc-event-main-frame">
                        <div class="fc-event-title-container">
                            <div class="fc-event-title">${arg.event.title}</div>
                        </div>
                    </div>`
                };
            }
        });
        
        calendar.render();
        setInterval(() => {
            calendar.refetchEvents();
        }, 30000);
    }
});

axios.interceptors.response.use(
    response => response,
    error => {
        if (error.response && error.response.status === 401) {
            window.location.href = '/login';
        }
        return Promise.reject(error);
    }
);