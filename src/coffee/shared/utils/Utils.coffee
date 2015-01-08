class Utils

	@getCoverSizeImage: (picWidth, picHeight, containerWidth, containerHeight) =>

		pw = picWidth
		ph = picHeight
		cw = containerWidth || W.ww
		ch = containerHeight || W.wh

		pr = pw / ph
		cr = cw / ch

		if cr < pr
			return {
				'width': ch * pr
				'height': ch
				'top': 0
				'left': - ((ch * pr) - cw) * 0.5
			}

		else
			return {
				'width': cw
				'height': cw / pr
				'top': - ((cw / pr) - ch) * 0.5
				'left': 0
			}


	@getContainSizeImage: (picWidth, picHeight, containerWidth, containerHeight) =>

		pw = picWidth
		ph = picHeight
		cw = containerWidth || W.ww
		ch = containerHeight || W.wh

		pr = pw / ph
		cr = cw / ch

		if cr < pr
			return {
				'width': cw
				'height': cw / pr
				'top': (ch - cw / pr) * 0.5
				'left': 0
			}

		else
			return {
				'width': ch * pr
				'height': ch
				'top': 0
				'left': (cw - ch * pr) * 0.5
			}


	@clearTimers: (timers) =>	
		$.each timers, (key, timer) =>
			clearTimeout(timer)

	@hexToRgb: (hex) =>
		result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex)

		return if result then {
	        r: parseInt(result[1], 16),
	        g: parseInt(result[2], 16),
	        b: parseInt(result[3], 16)
	    } else null
    
