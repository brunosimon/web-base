class VideoPlayer_HTML

	constructor: (options) ->

		{@container, @src} = options

		@html = """
					<div class="video-container">
						<video>
						</video>
					</div>
				"""

		@videoContainer = $(@html)
		@$video = @videoContainer.children('video')
		@video = @$video[0]

		@volume = 1

		@setSrc(@src)
		@container.html(@videoContainer)

		#@mute()

		@_initEvents()


	# -------------------------------------------------------------o Listeners

	_initEvents: () =>

		@$video
			.on('load', @_onload)
			.on('canplay', @_onCanPlay)
			.on('progress', @_onProgress)
			.on('timeupdate', @_onTimeUpdate)
			.on('waiting', @_onWaiting)
			.on('playing', @_onPlaying)
			.on('ended', @_onEnded)
			.on('pause', @_onPause)


	# -------------------------------------------------------------o Private methods

	_onCanPlay: () =>

		#console.log 'HTML: onCanPlay'
		$(@).trigger(VideoPlayer.CANPLAY)
		#@video.play()


	_onLoad: () =>



	_onProgress: () =>

		$(@).trigger(VideoPlayer.PROGRESS)
		#console.log 'HTML: onProgress'


	_onTimeUpdate: () =>

		$(@).trigger(VideoPlayer.TIMEUPDATE)
		#console.log 'HTML: onTimeUpdate'


	_onWaiting: () =>

		$(@).trigger(VideoPlayer.WAITING)
		#console.log 'HTML: onWaiting'


	_onPlaying: () =>

		$(@).trigger(VideoPlayer.PLAYING)
		#console.log 'HTML: onPlaying'

	_onEnded: () =>

		$(@).trigger(VideoPlayer.ENDED)

	_onPause: () =>

		$(@).trigger(VideoPlayer.PAUSED)

		

	# -------------------------------------------------------------o Actions

	play: () =>

		@video.play()


	pause: () =>

		@video.pause()


	stop: () =>

		@video.currentTime = 0
		@video.pause()


	mute: () =>

		@video.volume = 0


	unmute: () =>

		@video.volume = @volume


	# -------------------------------------------------------------o Getter

	getCurrentTime: () =>

		return @video.currentTime


	getDuration: () =>

		return @video.duration

	getState: () =>

		return null

	getSrc: () =>

		return @video.src

	# -------------------------------------------------------------o Setters

	setVolume: (val) =>

		@volume = val
		@video.volume = val


	seek: (val) =>

		@video.currentTime = val


	setSrc: (src) =>

		if src
			@srcNoExt = src.substr(0, src.lastIndexOf( "." ))

		#@video.src([
		#	{ type: 'video/mp4', src: @srcNoExt + '.mp4' },
		#	{ type: 'video/webm', src: @srcNoExt + '.webm' },
		#	{ type: 'video/ogg', src: @srcNoExt + '.ogv' }
		#])

		sources = """
					<source src="#{@srcNoExt}.webm" type="video/webm" />
					<source src="#{@srcNoExt}.mp4" type="video/mp4" />
					<source src="#{@srcNoExt}.ogv" type="video/ogg" />
				"""
		
		@$video.html(sources)
		@video.load()



