// resources/js/app.js

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
            
            // Configuration de l'en-tête
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
            },
            
            // Vue par défaut
            initialView: 'dayGridMonth',
            
            // Paramètres d'affichage
            firstDay: 1, // Lundi comme premier jour
            navLinks: true, // Permet de cliquer sur les jours/semaines
            nowIndicator: true, // Affiche un indicateur de l'heure actuelle
            editable: true, // Permet de modifier les événements
            selectable: true, // Permet de sélectionner des créneaux
            dayMaxEvents: true, // Affiche "+X more" quand trop d'événements
            
            // Paramètres de localisation (français)
            locale: 'fr',
            buttonText: {
                today: 'Aujourd\'hui',
                month: 'Mois',
                week: 'Semaine',
                day: 'Jour',
                list: 'Liste'
            },
            
            // Source des événements
            events: {
                url: '/calendar/events',
                method: 'GET',
                failure: function() {
                    alert('Erreur lors du chargement des événements!');
                }
            },
            
            // Gestion de la sélection (création d'événement)
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
                        console.error(error);
                        alert('Erreur lors de la création!');
                    });
                }
                calendar.unselect();
            },
            
            // Clic sur un événement (modification/suppression)
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
                                console.error(error);
                                alert('Erreur lors de la suppression!');
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
                            console.error(error);
                            alert('Erreur lors de la modification!');
                        });
                    }
                }
            },
            
            // Déplacement d'événement
            eventDrop: function(info) {
                axios.put(`/calendar/${info.event.id}`, {
                    start: info.event.startStr,
                    end: info.event.endStr,
                    all_day: info.event.allDay
                })
                .catch(error => {
                    console.error(error);
                    info.revert();
                    alert('Erreur lors du déplacement!');
                });
            },
            
            // Redimensionnement d'événement
            eventResize: function(info) {
                axios.put(`/calendar/${info.event.id}`, {
                    start: info.event.startStr,
                    end: info.event.endStr
                })
                .catch(error => {
                    console.error(error);
                    info.revert();
                    alert('Erreur lors du redimensionnement!');
                });
            },
            
            // Personnalisation de l'apparence
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
        
        // Rafraîchissement automatique toutes les 30 secondes
        setInterval(() => {
            calendar.refetchEvents();
        }, 30000);
    }
});

// Gestion des erreurs axios
axios.interceptors.response.use(
    response => response,
    error => {
        if (error.response && error.response.status === 401) {
            window.location.href = '/login';
        }
        return Promise.reject(error);
    }
);