class Normalize

	@transform: (elm, transform) =>

		if elm
			elm.style.transform = transform
			elm.style.webkitTransform = transform
			elm.style.mozTransform = transform


	@transformOrigin: (elm, origin) =>

		if elm
			elm.style.transformOrigin = transform
			elm.style.webkitTransformOrigin = transform
			elm.style.mozTransformOrigin = transform
