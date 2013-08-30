$(function(){ mw.loader.using('ext.DFDiagram', function(){
	Tileset.Font.loadFromURL(wgScriptPath + '/extensions/DFDiagram/resources/8x12.png').on('ready', function(evt, font){
		$('.dfdiagram').each(function(i, e){
			$(e).find('table').hide();
			var rows = $(e).find('tr').length,
				cols = $(e).find('tr:nth(0) td').length,
				canvas = $('<canvas>').attr({
					width: cols * font.char_width,
					height: rows * font.char_height
				});
			$(e).append(canvas).find('table').hide();
			canvas = Tileset.Canvas(canvas, font);
			var rgb_arr = function(rgb_string) {
				return rgb_string.split('(')[1].split(')')[0].replace(/\s/g, '').split(',')
			};
			$(e).find('tr').each(function(row, tr){
				$(tr).find('td').each(function(col, td){
					var fg = $(td).find('span').css('color'),
						bg = $(td).find('span').css('background-color');
					canvas.draw_char($(td).text(), row, col, rgb_arr(fg), rgb_arr(bg));
				});
			});
		});
	});
});});
