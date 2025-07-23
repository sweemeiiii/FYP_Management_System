<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">
            {{ __('Calendar') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 bg-white shadow-md p-4 rounded">
            <div id="calendar"></div>
        </div>
    </div>

    <!-- FullCalendar CSS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
    <!-- FullCalendar JS -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <!-- jQuery (for AJAX) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('calendar');
        
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                selectable: true,
                editable: true,
                displayEventTime: false,
                eventDisplay: 'block',
                allDayDefault: true,
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },

                eventSources: [
                    @json($documentEvents),
                    {
                        url: '{{ route("calendar.events") }}',
                        method: 'GET',
                        failure: function () {
                            alert('There was an error while fetching events!');
                        }
                    }
                ],

                select: function (info) {
                    let title = prompt("Enter event title:");
                    if (title) {
                        $.post({
                            url: '{{ route("calendar.store") }}',
                            data: {
                                _token: '{{ csrf_token() }}',
                                title: title,
                                start: info.startStr,
                                end: info.endStr,
                            },
                            success: function () {
                                calendar.refetchEvents();
                            },
                            error:function(xhr) {
                                Swal.fire('Error!', 'Failed to create the event.', 'error');
                                console.error(xhr.responseText);
                            }
                        });
                    }
                    calendar.unselect();
                },

                eventClick: function (info) {
                    Swal.fire({
                        title: 'Edit Event',
                        input: 'text',
                        inputValue: info.event.title,
                        showCancelButton: true,
                        confirmButtonText: 'OK',
                        cancelButtonText: 'Delete',
                        showCloseButton: true,
                        preConfirm: (newTitle) => {
                            if (newTitle) {
                                const eventData = {
                                    id: info.event.id,
                                    title: newTitle,
                                    start: info.event.startStr.split('T')[0],
                                    allDay: true
                                };
                                return updateEvent(eventData);
                            }
                            return false;
                        }
                    }).then((result) => {
                        if (result.dismiss === Swal.DismissReason.cancel) {
                            Swal.fire({
                                title: 'Delete Event?',
                                text: 'Are you sure you want to delete this event?',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonText: 'Yes!',
                                cancelButtonText: 'No!'
                            }).then((deleteResult) => {
                                if (deleteResult.isConfirmed) {
                                    $.ajax({
                                        url: '{{ route("calendar.delete", ":id") }}'.replace(':id', info.event.id),
                                        type: 'DELETE',
                                        data: {
                                            _token: '{{ csrf_token() }}'
                                        },
                                        success: function () {
                                            calendar.refetchEvents();
                                            Swal.fire('Deleted!', 'The event has been deleted.', 'success');
                                        },
                                        error: function(xhr) {
                                            Swal.fire('Error!', 'Failed to delete the event.', 'error');
                                            console.error(xhr.responseText);
                                        }
                                    });
                                }
                            });
                        }
                    });
                },

                eventDrop: function (info) {
                    updateEvent(info.event);
                },
                eventResize: function (info) {
                    updateEvent(info.event);
                }
            });

        
            // Function to update event
            function updateEvent(event) {
                // Format date to be just the date part (no time)
                const dateStr = event.start;
                
                return $.ajax({
                    url: '{{ route("calendar.update", ":id") }}'.replace(':id', event.id),
                    type: 'PUT',
                    data: {
                        _token: '{{ csrf_token() }}',
                        title: event.title,
                        start: dateStr,
                        end: dateStr  // Same as start date to prevent stretching
                    },
                    success: function() {
                        calendar.refetchEvents();
                        Swal.fire('Updated!', 'Event has been updated.', 'success');
                    },
                    error: function(xhr) {
                        Swal.fire('Error!', 'Failed to update the event.', 'error');
                        console.error(xhr.responseText);
                        calendar.refetchEvents();
                    }
                });
            }
        
            calendar.render();
        });
        </script>
        
</x-app-layout>
