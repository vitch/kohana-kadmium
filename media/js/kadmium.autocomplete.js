$(
	function()
	{
		$('.js-autocomplete').each(
			function()
			{
				var container = $(this).parent(),
					label = container.find('label'),
					hiddenField = container.find('input'),
					hiddenId = hiddenField.attr('id'),
					textField = $('<input class="text autocomplete-inp" type="text" id="' + hiddenId + '-inp" />'),
					itemIdsByName = {},
					itemNames = [],
					itemHolder = $('<ul class="autocomplete-options" />'),
					initialIds = hiddenField.val().split(','),
					options = autocompleteOptions['options-' + hiddenId],
					isSortable = hiddenField.data('sortable')
					;
				label.attr('for', hiddenId + '-inp');
				container.append(
					itemHolder,
					textField
				);
				$.each(
					options,
					function(id, name)
					{
						itemNames.push(name);
						itemIdsByName[name] = id;
					}
				);
				hiddenField.val('');
				while (initialIds[0] == '') {
					initialIds.shift();
				}
				$.fx.off = true;
				$.each(
					initialIds,
					function(key, val)
					{
						addItem(options[val]);
					}
				);
				$.fx.off = false;
				function addItem(name)
				{
					var removeLink = $('<a class="js-remove-option">x</a>').bind(
							'click',
							function()
							{
								removeItem($(this).parent(), name);
							}
						),
						itemId = itemIdsByName[name] || name,
						currentIds = hiddenField.val().split(','),
						li = $('<li />').append(name, removeLink).attr('rel', itemId),
						existingPosition = $.inArray(itemId + '', currentIds);
					if (existingPosition > -1) {
						li = itemHolder.find('li:nth-child(' + (existingPosition+1) + ')');
					} else {
						itemHolder.append(li);
					}
					li.css('background-color', '#f6e6b1')
						.animate(
							{
								'backgroundColor' : '#f6f6f9'
							},
							500
						);
					if (existingPosition > -1) {
						return;
					}
					while (currentIds[0] == '') {
						currentIds.shift();
					}
					currentIds.push(itemId);
					hiddenField.val(currentIds.join(','));
				}
				function removeItem(ele, name)
				{
					var currentIds = hiddenField.val().split(','),
						idRemoved = itemIdsByName[name]
						;
					if (!idRemoved) {
						idRemoved = ele.val();
					}
					ele.remove();
					currentIds.splice(currentIds.indexOf(idRemoved), 1);
					hiddenField.val(currentIds.join(','));
				}
				textField.autocomplete(
					itemNames,
					{
						matchContains: hiddenField.data('match-contains')
					}
				).result(
					function(event, item)
					{
						addItem(item[0]);
						$(event.target).val('');
						event.stopImmediatePropagation();
					}
				).bind(
					'keypress',
					function(event)
					{
						if (event.keyCode == 13) {
							var val = textField.val();
							if (val != '') {
								return false;
							}
						}
					}
				);
				if (isSortable) {
					itemHolder.sortable(
						{
							items: '>li',
							stop: function(event, ui)
							{
								var ids = [];
								itemHolder.find('>li').each(
									function(i)
									{
										ids.push($(this).attr('rel'));
									}
								);
								hiddenField.val(ids.join(','));
							}
						}
					);
				}
			}
		);
	}
);

// https://github.com/jquery/jquery-color
(function(d){d.each(["backgroundColor","borderBottomColor","borderLeftColor","borderRightColor","borderTopColor","color","outlineColor"],function(f,e){d.fx.step[e]=function(g){if(!g.colorInit){g.start=c(g.elem,e);g.end=b(g.end);g.colorInit=true}g.elem.style[e]="rgb("+[Math.max(Math.min(parseInt((g.pos*(g.end[0]-g.start[0]))+g.start[0]),255),0),Math.max(Math.min(parseInt((g.pos*(g.end[1]-g.start[1]))+g.start[1]),255),0),Math.max(Math.min(parseInt((g.pos*(g.end[2]-g.start[2]))+g.start[2]),255),0)].join(",")+")"}});function b(f){var e;if(f&&f.constructor==Array&&f.length==3){return f}if(e=/rgb\(\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*\)/.exec(f)){return[parseInt(e[1]),parseInt(e[2]),parseInt(e[3])]}if(e=/rgb\(\s*([0-9]+(?:\.[0-9]+)?)\%\s*,\s*([0-9]+(?:\.[0-9]+)?)\%\s*,\s*([0-9]+(?:\.[0-9]+)?)\%\s*\)/.exec(f)){return[parseFloat(e[1])*2.55,parseFloat(e[2])*2.55,parseFloat(e[3])*2.55]}if(e=/#([a-fA-F0-9]{2})([a-fA-F0-9]{2})([a-fA-F0-9]{2})/.exec(f)){return[parseInt(e[1],16),parseInt(e[2],16),parseInt(e[3],16)]}if(e=/#([a-fA-F0-9])([a-fA-F0-9])([a-fA-F0-9])/.exec(f)){return[parseInt(e[1]+e[1],16),parseInt(e[2]+e[2],16),parseInt(e[3]+e[3],16)]}if(e=/rgba\(0, 0, 0, 0\)/.exec(f)){return a.transparent}return a[d.trim(f).toLowerCase()]}function c(g,e){var f;do{f=d.curCSS(g,e);if(f!=""&&f!="transparent"||d.nodeName(g,"body")){break}e="backgroundColor"}while(g=g.parentNode);return b(f)}var a={aqua:[0,255,255],azure:[240,255,255],beige:[245,245,220],black:[0,0,0],blue:[0,0,255],brown:[165,42,42],cyan:[0,255,255],darkblue:[0,0,139],darkcyan:[0,139,139],darkgrey:[169,169,169],darkgreen:[0,100,0],darkkhaki:[189,183,107],darkmagenta:[139,0,139],darkolivegreen:[85,107,47],darkorange:[255,140,0],darkorchid:[153,50,204],darkred:[139,0,0],darksalmon:[233,150,122],darkviolet:[148,0,211],fuchsia:[255,0,255],gold:[255,215,0],green:[0,128,0],indigo:[75,0,130],khaki:[240,230,140],lightblue:[173,216,230],lightcyan:[224,255,255],lightgreen:[144,238,144],lightgrey:[211,211,211],lightpink:[255,182,193],lightyellow:[255,255,224],lime:[0,255,0],magenta:[255,0,255],maroon:[128,0,0],navy:[0,0,128],olive:[128,128,0],orange:[255,165,0],pink:[255,192,203],purple:[128,0,128],violet:[128,0,128],red:[255,0,0],silver:[192,192,192],white:[255,255,255],yellow:[255,255,0],transparent:[255,255,255]}})(jQuery);