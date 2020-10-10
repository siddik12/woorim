jQuery(function () {
	jQuery.extend(jQuery.easing, {
		easeOutQuad: function (x, t, b, c, d) {
			return -c * (t /= d) * (t - 2) + b;
		}
	});

	jQuery('.ease-slide').each(function () {
		var viewport = jQuery(this),
		viewport_offset_x = viewport.offset().left,
		viewport_width = viewport.outerWidth(),
		outer_buffer_pct = 0.1,
		content = viewport.children('ul'),
		content_children = content,
		content_width = 0,
		content_width_fn = function () {
			content_width = 0;
	
			content.children('li').each(function () {
				content_width += jQuery(this).outerWidth();
			});

			content.width(content_width);

			content_width = content_width + parseInt(content.css('marginLeft'), 10) + parseInt(content.css('marginRight'), 10);
		},
		last_left = 0,
		freeze,
		move = function (pct) {
			if (freeze) {
				return;
			}

			var left = ((viewport_width - content_width) * Math.max(0, Math.min(1, (pct / (1 - (2 * outer_buffer_pct))) - outer_buffer_pct))) || 0,
			animationTime = Math.abs(last_left - left);

			if (animationTime > 50) {
				freeze = true;

				content.animate({
					left: left
				}, Math.floor(animationTime * 1.25), 'easeOutQuad', function () {
					freeze = false;
				});
			} else {
				content.css({
					left: left
				});
			}

			last_left = left;
		};

		content_width_fn();

		content.find('img').load(function () {
			content_width_fn();
		});

		viewport.unbind().mousemove(function (e) {
			var pct = (e.pageX - viewport_offset_x) / viewport_width;

			move(pct);
		}).find('a').unbind().focus(function (e) {
			var a = jQuery(this),
				pct = (a.position().left + (a.outerWidth() / 2)) / content_width;

			move(pct);
		});
	});
});