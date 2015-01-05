class VideoPlayer_Controls

	constructor: (options) ->

		{@container, @poster, @autoplay, @hideControlsAllowed} = options

		@_areControlsHidden = false

		@_initInterface()
		@_initEvents()

	# --------------------------------------------------------------------o template

	@TEMPLATE = """
				"""

	@SHIELD_CLICK: 'shield_click'
	@PLAY: 'play'
	@PAUSE: 'pause'
	@STOP: 'stop'
	@FULLSCREEN: 'fullscreen'
	@VOLUME: 'volume'

	@SEEK_START: 'seek_start'
	@SEEK_END: 'seek_end'
	

	# --------------------------------------------------------------------o

	_initInterface:() ->

		@controls = $(VideoPlayer_Controls.TEMPLATE)
		@container.append(@controls)

		if @autoplay != true && @poster
			@poster = $("""<img src="#{@poster}" class="poster" />""")
			@container.append(@poster)

		@shield = @container.find('.shield')

		@playButton = @container.find('.play-button')
		@pauseButton = @container.find('.pause-button')
		@stopButton = @container.find('.stop-button')
		@fullscreenButton = @container.find('.fullscreen-button')
		@volumeButton = @container.find('.volume-button')

		@timelineContainer = @container.find('.timeline-container')
		@timelineProgress = @timelineContainer.children('.progress')

		@timeContainer = @container.find('.time-container')
		@timeCurrent = @timeContainer.find('.time-current')
		@timeTotal = @timeContainer.find('.time-total')

		@progressBar = new UI_Slider({
			container: @timelineContainer
		})


	_initEvents: () ->

		@shield.on(Event.CLICK, @_onShieldClick)

		@playButton.on(Event.CLICK, @_onPlayButtonClick)
		@pauseButton.on(Event.CLICK, @_onPauseButtonClick)
		@stopButton.on(Event.CLICK, @_onStopButtonClick)
		@fullscreenButton.on(Event.CLICK, @_onFullscreenButtonClick)
		@volumeButton.on(Event.CLICK, @_onVolumeButtonClick)

		$(@progressBar).on(UI_Slider.START, @_onProgressBarStart)
		$(@progressBar).on(UI_Slider.CHANGE, @_onProgressBarChange)
		$(@progressBar).on(UI_Slider.END, @_onProgressBarEnd)

		if @hideControlsAllowed
			@container.on(Event.MOUSEMOVE, @_onMouseMove)


	# --------------------------------------------------------------------o private

	_onShieldClick: () =>

		$(@).trigger(VideoPlayer_Controls.SHIELD_CLICK)


	_onPlayButtonClick: () =>

		$(@).trigger(VideoPlayer_Controls.PLAY)


	_onPauseButtonClick: () =>

		$(@).trigger(VideoPlayer_Controls.PAUSE)


	_onStopButtonClick: () =>

		$(@).trigger(VideoPlayer_Controls.STOP)


	_onFullscreenButtonClick: () =>

		$(@).trigger(VideoPlayer_Controls.FULLSCREEN)


	_onVolumeButtonClick: () =>

		$(@).trigger(VideoPlayer_Controls.VOLUME)


	_onProgressBarStart: () =>

		$(@).trigger(VideoPlayer_Controls.SEEK_START)
		@timelineProgress.css({
			'width': @progressBar.perc * @timelineContainer.width()
		})


	_onProgressBarChange: () =>

		@timelineProgress.css({
			'width': @progressBar.perc * @timelineContainer.width()
		})

	_onProgressBarEnd: () =>

		$(@).trigger(VideoPlayer_Controls.SEEK_END, [@progressBar.perc])

	_onMouseMove: () =>

		@show()
		clearTimeout(@_moveTimer)
		@_moveTimer = setTimeout () =>
			@hide()
		, @_hideControlsDelay


	_changeTime: (perc) =>

		newTime = @_sortTime(perc * @duration)

		if (newTime != @timeText)
			@timeText = newTime
			@timeCurrent.text(newTime)
		

	_sortTime: (time) =>

		m = ~~ (time / 60)
		s = ~~ (time - m * 60)

		return (if (m < 10) then '0' + m else m) + ':' + (if (s < 10) then '0' + s else s)


	# --------------------------------------------------------------------o public

	onPlay: () =>

		@container.addClass('playing').removeClass('paused stopped')


	onPause: () =>

		@container.addClass('paused').removeClass('playing stopped')


	onBuffering: () =>


	onUpdate: (perc) =>

		@timelineProgress.css({
			'width': perc * @timelineContainer.width()
		})
		@_changeTime(perc)

	onStop: () =>

		@container.addClass('stopped').removeClass('playing paused')
		@timelineProgress.css({
			'width': 0
		})

	onSeek: (perc) =>

		@timelineProgress.css({
			'width': perc * @timelineContainer.width()
		})
		@_changeTime(perc)

	onMute: () =>

		@container.addClass('muted')


	onUnMute: () =>

		@container.removeClass('muted')


	hide: () =>

		if @_areControlsHidden == false
			@controls.addClass('hidden')
			@_areControlsHidden = true

	show: () =>

		if @_areControlsHidden == true
			@controls.removeClass('hidden')
			@_areControlsHidden = false


	setTotalTime: (duration) =>

		@duration = duration
		@timeTotal.text(@_sortTime(duration));












