class CalendarApp {

	constructor(){

		// stores registrations returned from retreat guru
		this.registrations = {};

		// stores registrations returned from retreat guru
		this.AdditionalRegistrationData = {};

		// Store registration data per date
		this.calendarDayData = {};

		// Store registration data per date
		this.daysAvailable = 0;

		// get all day elements
		this.days = document.querySelectorAll( ".days li a" );

		// store the current registration being viewed
		this.currentRegistration = 0;

		// last day clicked
		this.lastDayClicked = false;

		// load and save rest data
		this.loadRegistrations();

		this.addEvents();
	}

	loadRegistrations(){

		// Get the registration data from retreat guru
		fetch( 'https://demo14.secure.retreat.guru/api/v1/registrations?token=ef061e1a717568ee5ca5c76a94cf5842', {
			method: 'get',
		} )
			.then( function ( response ){
				// get the json body
				return response.json();
			} )
			.then( ( data ) => {
				if ("error" in data) {
					// Do something with the error
					alert( data.error )
				} else {
					// store the data
					this.registrations = data;

					// Normalize data
					this.normalizeData();

					// load the data into the UI
					this.loadUI();
				}
			} )
			.catch( function ( err ){
				// do something with the error
				console.log( err );
				alert( err );
			} );

		// Get the registration data from retreat guru
		fetch( 'http://instinctive-bear.flywheelsites.com/calendar/', {
			method: 'get',
		} )
			.then( function ( response ){
				// get the json body
				return response.json();
			} )
			.then( ( data ) => {
				if ("error" in data) {
					// Do something with the error
					alert( data.error )
				} else {
					// store the data
					this.AdditionalRegistrationData = data;
				}
			} )
			.catch( function ( err ){
				// do something with the error
				console.log( err );
				alert( err );
			} );

	}

	addEvents(){
		document.getElementById( 'retreat-calendar-guest-name' ).addEventListener( "change", ( event ) => {

			// get registration ID
			let registrationId = event.target.value;

			let detailsStatus = document.getElementById( 'retreat-calendar-status' );
			let detailsFrom   = document.getElementById( 'retreat-calendar-from' );
			let detailsTo     = document.getElementById( 'retreat-calendar-to' );

			// loop through registration and find the first registration ID that matches and fill the side bar
			// keep looping add add the other names to the select name drop down
			this.registrations.forEach( registration => {
				if (registrationId === registration.id.toString()) {
					this.currentRegistration = registration.id;
					detailsStatus.innerText  = registration.status;
					detailsFrom.innerText    = registration.start_date;
					detailsTo.innerText      = registration.end_date;
					this.setDetailsColColor( registration.status );
					this.setAdditionalInfo( registration.id );
				}
			} );
		} );

		document.getElementById( 'retreat-calendar-flight-info' ).addEventListener( "blur", ( event ) => {
			this.saveData( 'flight-info', event )
		} );

		document.getElementById( 'retreat-calendar-select-diet' ).addEventListener( "change", ( event ) => {
			this.saveData( 'diet', event )
		} );

		document.getElementById( 'retreat-calendar-add-yoga' ).addEventListener( "change", ( event ) => {
			this.saveData( 'yoga', event )
		} );

		document.getElementById( 'retreat-calendar-add-juice' ).addEventListener( "change", ( event ) => {
			this.saveData( 'juice', event )
		} );

		document.getElementById( 'retreat-calendar-add-massage' ).addEventListener( "change", ( event ) => {
			this.saveData( 'massage', event )
		} );

		document.getElementById( 'retreat-calendar-add-breath' ).addEventListener( "change", ( event ) => {
			this.saveData( 'breath', event )
		} );
	}

	saveData( key, event ){

		event.target.disabled = true;

		let value;

		if ('checkbox' === event.target.type) {
			value = event.target.checked;
		} else {
			value = event.target.value;
		}

		// Get the registration data from retreat guru
		fetch( 'http://instinctive-bear.flywheelsites.com/calendar/?registrationID=' + this.currentRegistration + '&key=' + key + '&value=' + value, {
			method: 'get',
		} )
			.then( function ( response ){
				// get the json body
				return response.json();
			} )
			.then( ( data ) => {
				if ("error" in data) {
					// Do something with the error
					alert( data.error )
				} else {
					// store the data
					this.AdditionalRegistrationData = data;
					event.target.disabled           = false;
				}
			} )
			.catch( function ( err ){
				// do something with the error
				console.log( err );
				alert( err );
			} );
	}

	normalizeData(){

		[...this.registrations].forEach( registration => {

			if (6 !== registration.room_id) {
				return;
			}

			let startDate = new Date( registration.start_date + " 00:00:00 GMT-0700" );
			let endDate   = new Date( registration.end_date + " 00:00:00 GMT-0700" );

			this.getDates( startDate, endDate ).forEach( date => {

				let registrationDate = this.dateToYMD( date );

				if (!( registrationDate in this.calendarDayData )) {
					this.calendarDayData[registrationDate] = {
						registrationIds: [],
						names: [],
						status: {
							occupied: false,
							pending: false,
						},

					}
				}

				this.calendarDayData[registrationDate].registrationIds.push( registration.id );
				this.calendarDayData[registrationDate].names.push( registration.full_name );

				if ("reserved" === registration.status) {
					this.calendarDayData[registrationDate].status.occupied = true;
				}

				if ("pending" === registration.status) {
					this.calendarDayData[registrationDate].status.pending = true;
				}

			} );
		} );
	}

	loadUI(){

		[...this.days].forEach( day => {

			let date = day.dataset.day;

			if (date in this.calendarDayData) {

				day.dataset.registrationIds = this.calendarDayData[date].registrationIds.join( ',' );
				day.setAttribute( 'title', this.calendarDayData[date].names.join( ',' ) );
				day.classList.add( this.getStatusClass( this.calendarDayData[date].status ) );

				day.addEventListener( "click", ( event ) => {

					if (this.lastDayClicked) {
						this.lastDayClicked.classList.remove( 'selected' );
					}

					this.lastDayClicked = event.target;

					event.target.classList.add( 'selected' );
					// get registration IDs
					let registrationIds = event.target.dataset.registrationIds.split( ',' );

					let firstRegistrationPopulated = false;

					let detailsSelectNames = document.getElementById( 'retreat-calendar-guest-name' );
					let detailsStatus      = document.getElementById( 'retreat-calendar-status' );
					let detailsFrom        = document.getElementById( 'retreat-calendar-from' );
					let detailsTo          = document.getElementById( 'retreat-calendar-to' );

					// loop through registration and find the first registration ID that matches and fill the side bar
					// keep looping add add the other names to the select name drop down
					this.registrations.forEach( registration => {
						if (registrationIds.includes( registration.id.toString() )) {
							if (!firstRegistrationPopulated) {
								this.currentRegistration          = registration.id;
								detailsSelectNames.options.length = 0;
								detailsStatus.innerText           = registration.status;
								detailsFrom.innerText             = registration.start_date;
								detailsTo.innerText               = registration.end_date;
								firstRegistrationPopulated        = true;
								this.setDetailsColColor( registration.status );
								this.setAdditionalInfo( registration.id )
							}
							let option       = document.createElement( 'option' );
							option.value     = registration.id;
							option.innerHTML = registration.full_name;
							detailsSelectNames.appendChild( option );
						}
					} );
				} );
			} else if ( '' !== date) {
				this.daysAvailable++;
			}
		} );

		document.getElementById( 'retreat-calendar-days-available' ).innerText = this.daysAvailable + ' available days';
	}

	getStatusClass( status ){
		if (status.pending && status.occupied) {
			return 'pending-occupied'
		}
		if (status.pending) {
			this.daysAvailable++;
			return 'pending'
		}
		if (status.occupied) {
			return 'occupied'
		}
		return ''
	}

	setDetailsColColor( status ){


		let detailsColumn = document.getElementById( 'retreat-calendar--col-details' );

		if ('pending' === status) {
			detailsColumn.classList.remove( 'retreat-calendar--col-occupied' );
			detailsColumn.classList.add( 'retreat-calendar--col-pending' );
		}

		if ('reserved' === status) {
			detailsColumn.classList.remove( 'retreat-calendar--col-pending' );
			detailsColumn.classList.add( 'retreat-calendar--col-occupied' );
		}
	}

	setAdditionalInfo( regId ){

		let flight   = document.getElementById( 'retreat-calendar-flight-info' );
		flight.value = '';

		let diet   = document.getElementById( 'retreat-calendar-select-diet' );
		diet.value = 'none';

		let yoga     = document.getElementById( 'retreat-calendar-add-yoga' );
		yoga.checked = false;

		let juice     = document.getElementById( 'retreat-calendar-add-juice' );
		juice.checked = false;

		let massage     = document.getElementById( 'retreat-calendar-add-massage' );
		massage.checked = false;

		let breath     = document.getElementById( 'retreat-calendar-add-breath' );
		breath.checked = false;

		if (regId in this.AdditionalRegistrationData) {
			if ('flight-info' in this.AdditionalRegistrationData[regId]) {
				flight.value = this.AdditionalRegistrationData[regId]['flight-info']
			}
			if ('diet' in this.AdditionalRegistrationData[regId]) {
				diet.value = this.AdditionalRegistrationData[regId]['diet']
			}
			if ('yoga' in this.AdditionalRegistrationData[regId]) {
				if ('true' === this.AdditionalRegistrationData[regId]['yoga']) {
					yoga.checked = true;
				}
			}
			if ('juice' in this.AdditionalRegistrationData[regId]) {
				if ('true' === this.AdditionalRegistrationData[regId]['juice']) {
					juice.checked = true;
				}
			}
			if ('massage' in this.AdditionalRegistrationData[regId]) {
				if ('true' === this.AdditionalRegistrationData[regId]['massage']) {
					massage.checked = true;
				}
			}
			if ('breath' in this.AdditionalRegistrationData[regId]) {
				if ('true' === this.AdditionalRegistrationData[regId]['breath']) {
					breath.checked = true;
				}
			}

		}
	}

	getDates( startDate, endDate ){
		let dates = [];

		while (startDate < endDate) {
			dates = [...dates, new Date( startDate )];
			startDate.setDate( startDate.getDate() + 1 );
		}
		return [...dates, endDate];
	}

	dateToYMD( date ){
		let d = date.getDate();
		let m = date.getMonth() + 1;
		let y = date.getFullYear();
		return '' + y + '-' + ( m <= 9 ? '0' + m : m ) + '-' + ( d <= 9 ? '0' + d : d );
	}
}

export default CalendarApp;