( function() {
	tinymce.PluginManager.add( 'cool_timeline', function( editor, url ) {
		var layouts=[{"text":'Default Layout',"value":'default'},
		{"text":'One Side Layout',"value":'one-side'},
		 {"text":'Compact Layout',"value":'compact'}
		];
		var skins=[{"text":"default","value":"default"},
		{"text":"light","value":"light"},
		{"text":"dark","value":"dark"}
		];
 
		var multi_items={
			"items":[
				{"text":"Select items","value":""},
				{"text":"1","value":"1"},
				{"text":"2","value":"2"},
				{"text":"3","value":"3"},
				{"text":"4","value":"4"},
					] };
 
		var animations_eff={
			"animations":[
				{"text":"None","value":"none"},
				{"text":"fade","value":"fade"},
				{"text":"fade-up","value":"fade-up"},
				{"text":"fade-down","value":"fade-down"},
				{"text":"fade-left","value":"fade-left"},
				{"text":"fade-right","value":"fade-right"},
				{"text":"fade-up-right","value":"fade-up-right"},
				{"text":"fade-up-left","value":"fade-up-left"},
				{"text":"fade-down-right","value":"fade-down-right"},
				{"text":"fade-down-left","value":"fade-down-left"},
				{"text":"flip-up","value":"flip-up"},
				{"text":"flip-down","value":"flip-down"},
				{"text":"flip-left","value":"flip-left"},
				{"text":"flip-right","value":"flip-right"},
				{"text":"slide-up","value":"slide-up"},
				{"text":"slide-down","value":"slide-down"},			
				{"text":"slide-left","value":"slide-left"},
				{"text":"slide-right","value":"slide-right"},
				{"text":"zoom-in","value":"zoom-in"},
				{"text":"zoom-in-up","value":"zoom-in-up"},
				{"text":"zoom-in-down","value":"zoom-in-down"},
				{"text":"zoom-in-left","value":"zoom-in-left"},
				{"text":"zoom-in-right","value":"zoom-in-right"},
				{"text":"zoom-out","value":"zoom-out"},
				{"text":"zoom-out-up","value":"zoom-out-up"},
				{"text":"zoom-out-down","value":"zoom-out-down"},
				{"text":"zoom-out-left","value":"zoom-out-left"},
				{"text":"zoom-out-right","value":"zoom-out-right"},
				{"text":"skew","value":"skew"},
				{"text":"scale","value":"scale"},
				{"text":"rotate","value":"rotate"},		
		]};
		 var date_formats={
			"formats":[
				  {"text":"Default","value":"default"},
				{"text":"F j","value":"F j"},
				{"text":"F j Y","value":"F j Y"},
				{"text":"Y-m-d","value":"Y-m-d"},
				{"text":"m/d/Y","value":"m/d/Y"},
				{"text":"d/m/Y","value":"d/m/Y"},
				{"text":"F j Y g:i A","value":"F j Y g:i A"},
				{"text":"Y","value":"Y"},
				{"text":"Custom","value":"custom"}
				]};
 
 
		var timeline_designs={
			"designs":[
				{"text":"Default","value":"default"},
				{"text":"Flat Design","value":"design-2"},
				{"text":"Classic Design","value":"design-3"},
				{"text":"Elegant Design","value":"design-4"},
				{"text":"Clean Design","value":"design-5"},
				{"text":"Modern Design","value":"design-6"}
			  ]
		  }
		  var compact_ele_pos=[
		  {"text":"On top date/label below title","value":"main-date"},
		   {"text":"On top title below date/label","value":"main-title"}
		  ];
	 
		var s_order=[{"text":"DESC","value":"DESC"},
				 {"text":"ASC","value":"ASC"}
		];
		 var ap_settings=[{"text":"False","value":"false"},
				 {"text":"True","value":"true"}
		];
	   var s_cont=[{"text":"Summary","value":"short"},
		 {"text":"Full Text","value":"full"}
		 ];
	  
		var icons_options=[{"text":"NO","value":"NO"},
		{"text":"YES","value":"YES"}];
	  
		 var timeline_based_on=[{"text":"Default(Date Based)","value":"default"},
		 {"text":"Custom Order","value":"custom"}
		  ];
		 var pagination=[{"text":"Default","value":"default"},
						 {"text":"Ajax Load More","value":"ajax_load_more"}
						 ];
		  var filters=[{"text":"No","value":"no"},
						 {"text":"Yes","value":"yes"}];		
 
 if(typeof ctl_cat_obj != 'undefined' && typeof ctl_cat_obj.category != 'undefined') 
 {	
	 var ctl_cats=JSON.parse(ctl_cat_obj.category);
	 var categories=[];
	 for( var cat in ctl_cats){
			categories.push({"text":ctl_cats[cat],"value":cat});
		}
	 
	 editor.addButton( 'cool_timeline_shortcode_button', {
		 
				 text: false,
				 type: 'menubutton',
				 image: url + '/cooltimeline.png',
				 menu: [
				 {
					 text: 'Vertical Timeline',
					 onclick: function() {
 
						 editor.windowManager.open( {
							 title: 'Add Cool Timeline Shortcode',
							 body: [
 
							 {
								 type: 'listbox', 
								 name: 'category', 
								 label: 'Timeline categories',
								 'values':categories
							 },
							 {
								 type: 'listbox',
								 name: 'timeline_layout',
								 label: 'Timeline Layouts',
								 'values':layouts
							 },
								 {
									 type: 'listbox',
									 name: 'designs',
									 label: 'Timeline Designs',
									 'values':timeline_designs.designs
								 },
							 
							 {
								 type: 'listbox',
								 name: 'timeline_skin',
								 label: 'Timeline skins',
								 'values':skins
							 },
							 {
								 type: 'listbox',
								 name: 'stories_order',
								 label: 'Story Order',
								 'values':s_order
							 },
							 {
									 type: 'listbox',
									 name: 'date_format',
									 label: 'Date formats',
									 'values':date_formats.formats
								 },
								 {
									 type: 'listbox',
									 name: 'tm_bs_on',
									 label: 'Timeline based on',
									 'values':timeline_based_on
								 },
								 {
									 type: 'textbox',
									 name: 'number_of_posts',
									 label: 'Show number of Stories',
									 value:10
								 },
								 {
										 type: 'listbox',
										 name: 'story_content',
										 label: 'Stories Description?',
										 'values':s_cont
								 },
								 {
									 type: 'listbox',
									 name: 'ctl_icons',
									 label: 'Icon',
									 'values':icons_options
								 },
								 {
									 type: 'listbox',
									 name: 'compact_ele_pos',
									 label: 'Compact Layout Date&Title positon',
									 values:compact_ele_pos
								 },
								 {
									 type: 'listbox',
									 name: 'pagination',
									 label: 'Pagination ?',
									 values:pagination
								 },
								 {
									 type: 'listbox',
									 name: 'filters',
									 label: 'Enable category filters ?',
									 values:filters
								 },
								 {
									 type: 'listbox',
									 name: 'animations',
									 label: 'Animation Effects',
									 values:animations_eff.animations
								 }
							 ],
							 onsubmit: function( e ) {
								 editor.insertContent( '[cool-timeline layout="'+ e.data.timeline_layout+'"  designs="'+ e.data.designs +'" skin="'+ e.data.timeline_skin+'" category="' + e.data.category + '" show-posts="' + e.data.number_of_posts + '" order="' + e.data.stories_order + '" icons="' + e.data.ctl_icons + '" animations="' + e.data.animations + '" date-format="' + e.data.date_format + '" story-content="'+ e.data.story_content +'" based="'+ e.data.tm_bs_on +'" compact-ele-pos="'+ e.data.compact_ele_pos +'" pagination="'+ e.data.pagination +'" filters="'+ e.data.filters +'"]');
							 }
						 });
					 }
				 },
 
				 {
						 text: 'Horizontal Timeline',
						 onclick: function() {
 
							 editor.windowManager.open( {
								 title: 'Add Cool Timeline Shortcode',
								 body: [
									 {
										 type: 'listbox',
										 name: 'category',
										 label: 'Timeline categoires',
										 'values':categories
									 },
 
									 {
										 type: 'listbox',
										 name: 'designs',
										 label: 'Timeline Designs',
										 'values':timeline_designs.designs
									 },
									 {
										 type   : 'container',
										 name   : 'display-lbl',
										 label  : '',
										 html   : '<i>Display stories option is not for default design.</i>'
									 },
									 {
										 type: 'listbox',
										 name: 'items',
										 label: 'Display stories',
										 'values':multi_items.items
									 },
									 {
										 type: 'listbox',
										 name: 'timeline_skin',
										 label: 'Timeline skins',
										 'values':skins
									 },
									 {
										 type: 'listbox',
										 name: 'stories_order',
										 label: 'Story Order',
										 'values':s_order
									 },
									 {
									 type: 'listbox',
									 name: 'date_format',
									 label: 'Date formats',
									 'values':date_formats.formats
								 },
								 {
									 type: 'listbox',
									 name: 'tm_bs_on',
									 label: 'Timeline based on',
									 'values':timeline_based_on
								 },
									 {
										 type: 'textbox',
										 name: 'number_of_posts',
										 label: 'Show number of posts',
										 value:20
									 },
									 {
										 type: 'listbox',
										 name: 'story_content',
										 label: 'Stories Description?',
										 'values':s_cont
									 },
									 {
										 type: 'listbox',
										 name: 'autoplay',
										 label: 'Auto play settings?',
										 'values':ap_settings
									 },
									 {
										 type: 'textbox',
										 name: 'start_on',
										 label: 'Timeline Starting from Story e.g(2)',
										 value:0
									 },
									 {
										 type: 'listbox',
										 name: 'ctl_icons',
										 label: 'Icon',
										 'values':icons_options
									 }
								 ],
								 onsubmit: function( e ) {
									 editor.insertContent( '[cool-timeline layout="horizontal" category="' + e.data.category + '" skin="'+ e.data.timeline_skin+'"  designs="'+ e.data.designs +'" show-posts="' + e.data.number_of_posts + '" order="' + e.data.stories_order + '" items="' + e.data.items + '"  icons="' + e.data.ctl_icons + '" story-content="'+ e.data.story_content +'"  date-format="' + e.data.date_format + '" based="'+ e.data.tm_bs_on +'" autoplay="'+ e.data.autoplay +'" start-on="'+ e.data.start_on +'"]');
								 }
							 });
						 }
					 },
					 {
						 text: 'Vertical Content Timeline(Blog)',
						 onclick: function() {
 
							 editor.windowManager.open( {
								 title: 'Add Vertical Content Timeline Shortcode',
								 body: [
									 {
										 type: 'textbox',
										 name: 'post_type',
										 label: 'Content Post type',
										 value:'post'
									 },
									 {
										 type: 'textbox',
										 name: 'taxonomy_name',
										 label: 'Taxonomy Name',
										 value:'category'
									 },
									 {
										 type: 'textbox',
										 name: 'post_category',
										 label: 'Specific category(s) (Add category(s) slug - comma separated)',
										 value:''
									 },
									 {
										 type: 'textbox',
										 name: 'tags',
										 label: 'Specific tags(add tags slug)',
										 value:''
									 },
									 
									 {
										 type: 'listbox',
										 name: 'timeline_layout',
										 label: 'Timeline Layouts',
										 'values':[{"text":'Default Layout',"value":'default'},
											 {"text":'One Side Layout',"value":'one-side'},
											 {"text":'Compact Layout',"value":'compact'},
										 ]
									 },
									 {
										 type: 'listbox',
										 name: 'designs',
										 label: 'Timeline Designs',
										 'values':timeline_designs.designs
									 },
									 {
										 type: 'listbox',
										 name: 'timeline_skin',
										 label: 'Timeline skins',
										 'values':skins
									 },
									 {
										 type: 'listbox',
										 name: 'stories_order',
										 label: 'Story Order',
										 'values':s_order
									 },
									 {
									 type: 'listbox',
									 name: 'date_format',
									 label: 'Date formats',
									 'values':date_formats.formats
									 },
									 {
										 type: 'textbox',
										 name: 'number_of_posts',
										 label: 'Show number of posts',
										 value:10
									 },
									 {
										 type: 'listbox',
										 name: 'ctl_icons',
										 label: 'Icon',
										 'values':icons_options
									 },
									 {
										 type: 'listbox',
										 name: 'story_content',
										 label: 'Content Description?',
										 'values':s_cont
									 },
									 {
									 type: 'listbox',
									 name: 'pagination',
									 label: 'Pagination ?',
									 values:pagination
								 },
								 {
									 type: 'listbox',
									 name: 'filters',
									 label: 'Enable category filters ?(Add value in Taxonomy field before using it)',
									 values:filters
								 },
								 {
										 type: 'listbox',
										 name: 'animations',
										 label: 'Animation Effects',
										 'values':animations_eff.animations
									 }
								 ],
								 onsubmit: function( e ) {
									 editor.insertContent( '[cool-content-timeline post-type="'+ e.data.post_type+'"  post-category="' + e.data.post_category + '" tags="' + e.data.tags + '" story-content="'+ e.data.story_content +'"  taxonomy="' + e.data.taxonomy_name + '" layout="'+ e.data.timeline_layout+'"  designs="'+ e.data.designs +'" skin="'+ e.data.timeline_skin+'" show-posts="' + e.data.number_of_posts + '" order="' + e.data.stories_order + '"  icons="' + e.data.ctl_icons + '" animations="' + e.data.animations + '"  date-format="' + e.data.date_format + '"  pagination="'+ e.data.pagination +'"  filters="'+ e.data.filters +'"]');
								 }
							 });
						 }
					 },
					 {
		 text: 'Horizontal Content Timeline(Blog)',
		 onclick: function() {
 
			 editor.windowManager.open( {
				 title: 'Add Horizontal Content Timeline Shortcode',
				 body: [
					 {
					 type: 'textbox',
						 name: 'post_type',
						 label: 'Content Post type',
						 value:'post'
					 },
					 {
						 type: 'textbox',
						 name: 'taxonomy_name',
						 label: 'Taxonomy Name',
						 value:'category'
					 },
					 {
						 type: 'textbox',
						 name: 'post_category',
						 label: 'Specific category(s) (Add category(s) slug - comma separated)',
						 value:''
					 },
					 {
						 type: 'textbox',
						 name: 'tags',
						 label: 'Specific tags(add tags slug)',
						 value:''
					 },
					 {
						 type: 'listbox',
						 name: 'designs',
						 label: 'Timeline Designs',
						 'values':timeline_designs.designs
					 },
					 {
						 type: 'listbox',
						 name: 'items',
						 label: 'Display Stories(This option is not for default design)',
						 'values':multi_items.items
					 },
					 {
						 type: 'listbox',
						 name: 'autoplay',
						 label: ' Autoplay settings?',
						 'values':ap_settings
					 },
					 {
						 type: 'textbox',
						 name: 'start_on',
						 label: 'Timeline Starting from Story e.g(2)',
						 value:0
					 },
					 {
						 type: 'listbox',
						 name: 'timeline_skin',
						 label: 'Timeline skins',
						 'values':skins
					 },
					 {
						 type: 'listbox',
						 name: 'stories_order',
						 label: 'Story Order',
						 'values':s_order
					 },
					 {
					 type: 'listbox',
					 name: 'date_format',
					 label: 'Date formats',
					 'values':date_formats.formats
					 },
					 {
						 type: 'textbox',
						 name: 'number_of_posts',
						 label: 'Show number of posts',
						 value:10
					 },
					 {
						 type: 'listbox',
						 name: 'ctl_icons',
						 label: 'Icon',
						 'values':icons_options
					 },
					 {
						 type: 'listbox',
						 name: 'story_content',
						 label: 'Content Description?',
						 'values':s_cont
					 },
			 
				 ],
				 onsubmit: function( e ) {
					 editor.insertContent( '[cool-content-timeline post-type="'+ e.data.post_type+'"  post-category="' + e.data.post_category + '" tags="' + e.data.tags + '"  autoplay="' + e.data.autoplay + '" story-content="'+ e.data.story_content +'"  taxonomy="' + e.data.taxonomy_name + '" layout="horizontal"  designs="'+ e.data.designs +'" skin="'+ e.data.timeline_skin+'" show-posts="' + e.data.number_of_posts + '" order="' + e.data.stories_order + '"  start-on="' + e.data.start_on + '" icons="' + e.data.ctl_icons + '" items="' + e.data.items + '" date-format="' + e.data.date_format + '"]');
				 }
			 });
		 }
	 }]
			 });
	 
	 }
	 
	 });
	 
 
 })();