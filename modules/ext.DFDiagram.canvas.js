$(function(){
	var dfd = {}, dfd_globals = {font_path: wgScriptPath+'/images/0/02/Curses_640x300.png'};
	
	var Cache = dfd.Cache = function() {
		var _cache = {};
		var self = {};
		self.get = function(k) {
			return _cache[k];
		};
		self.set = function(k, v){
			_cache[k] = v; return v;
		};
		
		return self;
	};

	
	var Character = dfd.Character = function(font, ch, fg, bg){
		var self = {};
	};
	
	
	var FontCanvas = dfd.FontCanvas = function(font, image_url){
		var self = {};
		
		// Load font image - bmp = bitmap, but other formats are perfectly fine
		var bmp = self.bmp = $('<img>').attr('src', image_url);
		var bmp_canvas = self.bmp_canvas = $('<canvas>');
		bmp.ready(function(){
			var cv = bmp_canvas[0];
			cv.width = bmp[0].width;
			cv.height = bmp[0].height;
			cv.getContext('2d').drawImage(bmp[0], 0, 0);
		});
		
		return self;
	};
	
	var Font = dfd.Font = function(image_url){
		var self = {};
		
		self.font_canvas = FontCanvas(self, image_url);
		self.char_width = self.font_canvas.bmp[0].width / 16;
		self.char_height = self.font_canvas.bmp[0].height / 16;
		
		self.cache = Cache();
		
		self.get_char = function(ch, fg, bg){
			if (!fg) fg = 'rgb(255,255,255)';
			if (!bg) bg = 'rgb(0,0,0)';
			// Convert to rgb
			fg = $('<span>').css('background-color', fg).appendTo('body').css('background-color');
			bg = $('<span>').css('background-color', bg).appendTo('body').css('background-color');
			// Parse out numbers
			fg = fg.match(/\d+/g);
			bg = bg.match(/\d+/g);
			return Character(self, ch, fg, bg)
			
		};
		
		return self;
	};
	
	var Diagram = dfd.Diagram = function(opts){
		var self = {};
		opts = $.extend({
			'font': dfd_globals.font_path,
		}, opts);
		
		if (!('div' in opts)) {
			// div is required!
			return self;
		}
		
		self.font = Font(opts.font);
		self.div = $(opts.div);
		
		self.canvas = $('<canvas>');
		self.rows = self.div.find('tr').length;
		self.cols = self.div.find('tr:nth(0) td').length;
		self.width = self.cols * self.font.char_width;
		self.height = self.rows * self.font.char_height;
		
		self.canvas.height = self.height;
		self.canvas.width = self.width;

		return self;
	};
	
	function init_diagrams() {
		window.diagrams = [];
		$('.dfdiagram').each(function(i, e){
			e = $(e);
			var w = $('<div class="dfdiagram-wrapper">').insertAfter(e);
			e.appendTo(w).hide();
			var d = Diagram({'div': e});
			diagrams.push(d);
			d.canvas.appendTo(w);
		});
	}
	
	$(init_diagrams);
	
	window.dfd = dfd;
});
