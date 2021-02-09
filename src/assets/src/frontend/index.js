import './scss/main.scss';
import './js/calendar-app.js';

import CalendarApp from './js/calendar-app.js';

// Do on DOM ready
document.addEventListener( 'DOMContentLoaded', () => {
	new CalendarApp();
} );