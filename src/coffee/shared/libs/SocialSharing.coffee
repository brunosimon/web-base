class SocialSharing

	constructor: () ->

		@links = $('.social-link')

		@_initEvents()


	_initEvents: () =>

		$(document).on(Event.CLICK, '.social-link', @_onLinkClick)


	_onLinkClick: (e) =>

		e.preventDefault()

		link = $(e.currentTarget).attr('href')

		width = 800
		height = 500

		leftPosition = (window.screen.width / 2) - ((width / 2) + 10);
		topPosition = (window.screen.height / 2) - ((height / 2) + 50);

		options = windowFeatures = "status=no,height=" + height + ",width=" + width + ",resizable=yes,left=" + leftPosition + ",top=" + topPosition + ",screenX=" + leftPosition + ",screenY=" + topPosition + ",toolbar=no,menubar=no,scrollbars=no,location=no,directories=no"

		window.open(link,'Withings Activité',options);

