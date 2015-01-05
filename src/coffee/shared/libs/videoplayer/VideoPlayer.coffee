class VideoPlayer

	constructor: (options) ->

		{@container, @src, @poster, @autoplay, @loop, @hideControlsAllowed} = options

		@isMuted = false
		@state = -1
		@tempState = null
		@canPlay = false
		@queueSeek = null
		@bufferingInterval = null
		@isBuffering = false
		@isPaused = false

		@_initPlayer()


	_initPlayer: () ->

		@container.addClass('stopped')

		@player = new VideoPlayer_HTML({
			container: @container
			src: @container.attr('data-src') || @src
		})
		@video = @player.$video
		@videoContainer = @player.videoContainer

		@controls = new VideoPlayer_Controls({
			container: @container
			poster: @poster
			autoplay: @autoplay
			hideControlsAllowed: @hideControlsAllowed
		})

		@_initListeners()
		
		if @autoplay
			@play()


	_initListeners: () ->

		$(@controls)
			.on(VideoPlayer_Controls.SHIELD_CLICK, @_onShieldClick)
			.on(VideoPlayer_Controls.PLAY, @_onControlsPlay)
			.on(VideoPlayer_Controls.PAUSE, @_onControlsPause)
			.on(VideoPlayer_Controls.STOP, @_onControlsStop)
			.on(VideoPlayer_Controls.FULLSCREEN, @_onControlsFullscreen)
			.on(VideoPlayer_Controls.VOLUME, @_onControlsVolume)
			.on(VideoPlayer_Controls.SEEK_START, @_onControlsSeekStart)
			.on(VideoPlayer_Controls.SEEK_END, @_onControlsSeekEnd)

		$(@player)
			.on(VideoPlayer.CANPLAY, @_onPlayerCanPlay)
			.on(VideoPlayer.PROGRESS, @_onPlayerProgress)
			.on(VideoPlayer.TIMEUPDATE, @_onPlayerTimeUpdate)
			.on(VideoPlayer.WAITING, @_onPlayerWaiting)
			.on(VideoPlayer.PLAYING, @_onPlayerPlaying)
			.on(VideoPlayer.ENDED, @_onPlayerEnded)
			.on(VideoPlayer.PAUSED, @_onPlayerPaused)


	# -------------------------------------------------------------o Static

	@playerState: {
		ENDED: 1
		PLAYING: 2
		PAUSED: 3
		BUFFERING: 4
		CUED: 5
	}

	@CANPLAY: 'canplay'
	@PROGRESS: 'progress'
	@TIMEUPDATE: 'timeupdate'
	@WAITING: 'waiting'
	@PLAYING: 'playing'
	@SEEKED: 'seeked'
	@BUFFERING: 'buffering'
	@ENDED: 'ended'
	@PAUSED: 'paused'
	@SHIELD_CLICK: 'shield_click'
	@MUTE: 'onmute'
	@UNMUTE: 'onunmute'
	

	# -------------------------------------------------------------o Actions

	play: () =>

		@state = VideoPlayer.playerState.PLAYING
		@player.play()
		@controls.onPlay()


	pause: () =>

		@state = VideoPlayer.playerState.PAUSED
		@player.pause()
		@controls.onPause()


	stop: () =>

		@state = VideoPlayer.playerState.ENDED
		@player.stop()
		@controls.onStop()

		if @loop == true
			@play()


	mute: () =>

		@isMuted = true
		@player.mute()
		@controls.onMute()

		$(@).trigger(VideoPlayer.MUTE)


	unmute: () =>

		@isMuted = false
		@player.unmute()
		@controls.onUnMute()

		$(@).trigger(VideoPlayer.UNMUTE)


	# -------------------------------------------------------------o Getter

	getCurrentTime: () =>

		return @player.getCurrentTime()


	getDuration: () =>

		return @player.getDuration()

	getState: () =>

		return @state

	getSrc: () =>

		return @player.getSrc()


	# -------------------------------------------------------------o Setters

	setVolume: (val) =>

		@player.setVolume(val)


	seek: (val) =>

		if @canPlay == false
			@queueSeek = val
		else
			@player.seek(val)
			@controls.onSeek(val / @player.getDuration())
			$(@).trigger(VideoPlayer.SEEKED)


	setSrc: (src) =>

		@canPlay = false
		@player.setSrc(src)


	showControls: () =>

		@controls.show()

	hideControls: () =>

		@controls.hide()


	# -------------------------------------------------------------o Private methods

	_onShieldClick: () =>
		
		if @state == VideoPlayer.playerState.PLAYING
			@pause()
			$(@).trigger(VideoPlayer.SHIELD_CLICK)
		else
			@play()


	_onControlsPlay: () =>

		@play()


	_onControlsPause: () =>

		@pause()
		


	_onControlsStop: () =>

		@pause()
		@seek(0)


	_onControlsFullscreen: () =>



	_onControlsVolume: () =>

		if @isMuted == true
			@unmute()
		else
			@mute()


	_onControlsSeekStart: () =>

		@tempState = @state
		@pause()


	_onControlsSeekEnd: (e, perc) =>

		@seek(perc * @getDuration())
		if @tempState == VideoPlayer.playerState.PLAYING
			@play()


	_onPlayerCanPlay: () =>

		@canPlay = true
		if @queueSeek != null
			@seek(@queueSeek)
			@queueSeek = null

		@controls.setTotalTime(@getDuration())
		$(@).trigger(VideoPlayer.CANPLAY)


	_onPlayerProgress: () =>

		$(@).trigger(VideoPlayer.PROGRESS)


	_onPlayerTimeUpdate: () =>

		$(@).trigger(VideoPlayer.TIMEUPDATE)

		if @state == VideoPlayer.playerState.PLAYING
			@controls.onUpdate(@getCurrentTime() / @getDuration())

		#experimental
		###clearTimeout(@bufferingInterval)
		@bufferingInterval = setTimeout () =>
			if @state != VideoPlayer.playerState.PAUSED
				@state = VideoPlayer.playerState.BUFFERING
				@isBuffering = true
				$(@).trigger(VideoPlayer.BUFFERING)
		, 500###


	_onPlayerWaiting: () =>

		$(@).trigger(VideoPlayer.WAITING)


	_onPlayerPlaying: () =>

		@isBuffering = false

		$(@).trigger(VideoPlayer.PLAYING)


	_onPlayerEnded: () =>

		@stop()
		$(@).trigger(VideoPlayer.ENDED)


	_onPlayerPaused: () =>

		$(@).trigger(VideoPlayer.PAUSED)












