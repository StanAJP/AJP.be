<?php
/** no direct access **/
defined('MECEXEC') or die();

do_action('mec_start_skin', $this->id);
do_action('mecgeneral_calendar_skin_head');

// Monthpicker Assets
$this->main->load_month_picker_assets();

// Shortcode Options
$local_time = (isset($this->skin_options['include_local_time']) and !empty( $this->skin_options['include_local_time'] )) ? $this->skin_options['include_local_time'] : false;
$display_label = (isset($this->skin_options['display_label']) and !empty( $this->skin_options['display_label'] )) ? $this->skin_options['display_label'] : false;
$reason_for_cancellation = (isset($this->skin_options['reason_for_cancellation']) and !empty( $this->skin_options['reason_for_cancellation'] )) ? $this->skin_options['reason_for_cancellation'] : false;
$more_event =  (isset($this->skin_options['more_event']) and !empty( $this->skin_options['more_event'] )) ? (int) $this->skin_options['more_event'] : 10;
$sed_method =  '';
if (isset($this->skin_options['sed_method']) and !empty( $this->skin_options['sed_method'] )) {
	$sed_method = ($this->skin_options['sed_method']  == 'new') ? '_blank' : ($this->skin_options['sed_method']  == '0' ? '_self' : $this->skin_options['sed_method']);
}

// Shortcode Filters
$filter_category = get_post_meta($this->id, 'category', true) ?  get_post_meta($this->id, 'category', true) : '';
$filter_location = get_post_meta($this->id, 'location', true) ?  get_post_meta($this->id, 'location', true) : '';
$filter_organizer = get_post_meta($this->id, 'organizer', true) ?  get_post_meta($this->id, 'organizer', true) : '';
$filter_label = get_post_meta($this->id, 'label', true)  ?  get_post_meta($this->id, 'label', true) : '';
$filter_tag = get_post_meta($this->id, 'tag', true)  ?  get_post_meta($this->id, 'tag', true) : '';
$filter_author = get_post_meta($this->id, 'author', true)  ?  get_post_meta($this->id, 'author', true) : '';
$show_past_events = $this->atts['show_past_events'] ? $this->atts['show_past_events'] : '0';
$show_only_past_events = $this->atts['show_only_past_events'] ? $this->atts['show_only_past_events'] : '0';
$show_only_one_occurrence = (isset($this->atts['show_only_one_occurrence']) && $this->atts['show_only_one_occurrence'] != '0')  ?  '1' : '0';
$mec_tax_input = (isset($this->atts['mec_tax_input']) && $this->atts['mec_tax_input'] != '0')  ?  $this->atts['mec_tax_input'] : '';

// WordPress Options
$lang = substr(get_locale(), 0, strpos(get_locale(), "_"));
$is_category_page = is_tax('mec_category');
$cat_id = '';
if ($is_category_page) {
	$category = get_queried_object();
	$cat_id = $category->term_id;
}
$week_start_day = (int) get_option( 'start_of_week' );

if( !function_exists( 'mec_general_calendar_find_event' ) ){

	// Search Options
	function mec_general_calendar_find_event($sf_options, $find_filter) {
		if ($find_filter == 'find') {
				if (($sf_options['category']['type'] != '0' && !is_null($sf_options['category']['type']))  || ($sf_options['location']['type'] != '0' && !is_null($sf_options['location']['type'])) || ($sf_options['organizer']['type'] != '0' && !is_null($sf_options['organizer']['type'])) || ($sf_options['speaker']['type'] != '0' && !is_null($sf_options['speaker']['type'])) || ($sf_options['tag']['type'] != '0' && !is_null($sf_options['tag']['type'])) || ($sf_options['label']['type'] != '0' && !is_null($sf_options['label']['type'])) || ($sf_options['event_cost']['type'] != '0' && !is_null($sf_options['event_cost']['type'])) || ($sf_options['text_search']['type'] != '0' && !is_null($sf_options['text_search']['type'])) || ($sf_options['address_search']['type'] != '0' && !is_null($sf_options['address_search']['type'])) ) {
				return true;
			} else {
				return false;
			}
		}
		if ($find_filter == 'filter') {
				if (($sf_options['category']['type'] != '0' && !is_null($sf_options['category']['type']))  || ($sf_options['location']['type'] != '0' && !is_null($sf_options['location']['type'])) || ($sf_options['organizer']['type'] != '0' && !is_null($sf_options['organizer']['type'])) || ($sf_options['speaker']['type'] != '0' && !is_null($sf_options['speaker']['type'])) || ($sf_options['tag']['type'] != '0' && !is_null($sf_options['tag']['type'])) || ($sf_options['label']['type'] != '0' && !is_null($sf_options['label']['type'])) || ($sf_options['event_cost']['type'] != '0' && !is_null($sf_options['event_cost']['type'])) || ($sf_options['address_search']['type'] != '0' && !is_null($sf_options['address_search']['type'])) ) {
				return true;
			} else {
				return false;
			}
		}
	}
}

?>
<div class="mec-gCalendar" id="mec_skin_<?php echo $this->id; ?>">
	<div id='gCalendar-loading' class="mec-modal-result" style="display: none"></div>
	<div id="mec-gCalendar-wrap"></div>
	<div class="mec-gCalendar-filters">
		<div class="mec-gCalendar-filters-wrap">
		<?php
		echo $this->sf_options['category']['type'] != '0' ?  $this->sf_search_field('category',array('type'=>$this->sf_options['category']['type'])) : '';
		echo $this->sf_options['location']['type'] != '0' ?  $this->sf_search_field('location',array('type'=>$this->sf_options['location']['type'])) : '';
		echo $this->sf_options['organizer']['type'] != '0' ?  $this->sf_search_field('organizer',array('type'=>$this->sf_options['organizer']['type'])) : '';
		echo $this->sf_options['speaker']['type'] != '0' ?  $this->sf_search_field('speaker',array('type'=>$this->sf_options['speaker']['type'])) : '';
		echo $this->sf_options['tag']['type'] != '0' ?  $this->sf_search_field('tag',array('type'=>$this->sf_options['tag']['type'])) : '';
		echo $this->sf_options['label']['type'] != '0' ?  $this->sf_search_field('label',array('type'=>$this->sf_options['label']['type'])) : '';
		echo $this->sf_options['address_search']['type'] != '0' ?  $this->sf_search_field('address_search',array('type'=>$this->sf_options['address_search']['type'])) : '';
		echo $this->sf_options['event_cost']['type'] != '0' ?  $this->sf_search_field('event_cost',array('type'=>$this->sf_options['event_cost']['type'])) : '';
		?>
		</div>
	</div>
</div>
<style>.nice-select{color: #838383;-webkit-tap-highlight-color:transparent;background-color:#fff;border:solid 1px #E3E4E5;box-sizing:border-box;clear:both;cursor:pointer;display:block;float:left;font-family:inherit;font-size:12px;font-weight:400;height:42px;line-height:40px;outline:0;padding-left:18px;padding-right:30px;position:relative;text-align:left!important;-webkit-transition:all .2s ease-in-out;transition:all .2s ease-in-out;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none;white-space:nowrap;width: 100%;border-radius: 0 3px 3px 0;height: 40px;}.nice-select:hover{border-color:#dbdbdb}.nice-select:after{border-bottom: 1px solid #c1c2c3; border-right: 1px solid #c1c2c3; width: 8px; height: 8px; margin-top: -5px; right: 15px;content:'';display:block;pointer-events:none;position:absolute;top:50%;-webkit-transform-origin:66% 66%;-ms-transform-origin:66% 66%;transform-origin:66% 66%;-webkit-transform:rotate(45deg);-ms-transform:rotate(45deg);transform:rotate(45deg);-webkit-transition:all .15s ease-in-out;transition:all .15s ease-in-out}.nice-select.open:after{-webkit-transform:rotate(-135deg);-ms-transform:rotate(-135deg);transform:rotate(-135deg)}.nice-select.open .list{opacity:1;pointer-events:auto;-webkit-transform:scale(1) translateY(0);-ms-transform:scale(1) translateY(0);transform:scale(1) translateY(0)}.nice-select.disabled{border-color:#ededed;color:#999;pointer-events:none}.nice-select.disabled:after{border-color:#ccc}.nice-select.wide{width:100%}.nice-select.wide .list{left:0!important;right:0!important}.nice-select.right{float:right}.nice-select.right .list{left:auto;right:0}.nice-select.small{font-size:12px;height:36px;line-height:34px}.nice-select.small:after{height:4px;width:4px}.nice-select.small .option{line-height:34px;min-height:34px}.nice-select .list{width: 100%;background-color:#fff;border-radius:0 0 3px 3px;box-shadow:0 0 0 1px rgba(68,68,68,.11);box-sizing:border-box;margin-top:4px;opacity:0;overflow:hidden;padding:0;pointer-events:none;position:absolute;top:100%;left:0;-webkit-transform-origin:50% 0;-ms-transform-origin:50% 0;transform-origin:50% 0;-webkit-transform:scale(.75) translateY(-21px);-ms-transform:scale(.75) translateY(-21px);transform:scale(.75) translateY(-21px);-webkit-transition:all .2s cubic-bezier(.5,0,0,1.25),opacity .15s ease-out;transition:all .2s cubic-bezier(.5,0,0,1.25),opacity .15s ease-out;z-index:9}.nice-select .list:hover .option:not(:hover){background-color:transparent!important}.nice-select .option{cursor:pointer;font-weight:400;line-height:30px;list-style:none;min-height:30px;outline:0;padding-left:18px;padding-right:29px;text-align:left;-webkit-transition:all .2s;transition:all .2s;font-size: 14px;white-space: break-spaces;}.nice-select .option.focus,.nice-select .option.selected.focus,.nice-select .option:hover{background-color:#f6f6f6}.nice-select .option.selected{font-weight:700}.nice-select .option.disabled{background-color:transparent;color:#999;cursor:default}.no-csspointerevents .nice-select .list{display:none}.no-csspointerevents .nice-select.open .list{display:block}</style>
<script type="text/javascript" src="<?php echo $this->main->asset('js/jquery.nice-select.min.js'); ?>"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
		var calendarEl = document.getElementById("mec-gCalendar-wrap");
		var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
			initialDate: '<?php echo $this->get_start_date()[0].'-'.$this->get_start_date()[1].'-'.$this->get_start_date()[2]; ?>',
			editable: false,
			selectable: false,
			businessHours: false,
			height: 'auto',
			locale: '<?php echo $lang; ?>',
			<?php if (mec_general_calendar_find_event($this->sf_options, 'find')) : ?>
			customButtons: {
				findEvents: {
					text: '<?php echo esc_html__('Find Events', 'modern-events-calendar-lite'); ?>',
					click: function() {
						jQuery(".mec-gCalendar-filters").css('display' , 'none')
						var eventSource = [];
						eventSource = calendar.getEventSources();
						jQuery.each(eventSource, function (key, value) {
							value.remove();
						});
						calendar.addEventSource({
							url: '<?php echo get_rest_url(); ?>mec/v1/events',
							method: 'GET',
							startParam: 'startParam',
							endParam: 'endParam',
							textColor: '#000',
							ajax: true,
							extraParams: {
								show_past_events: <?php echo $show_past_events; ?>,
								show_only_past_events: <?php echo $show_only_past_events; ?>,
								show_only_one_occurrence: <?php echo $show_only_one_occurrence; ?>,
								categories: (jQuery('select[id^="mec_sf_category"]').length > 0) ?  jQuery('select[id^="mec_sf_category"]').val() : '',
								multiCategories: (jQuery('.select2-hidden-accessible').length > 0) ?JSON.stringify(jQuery('.select2-hidden-accessible').val())  : '',
								location: jQuery('select[id^="mec_sf_location"]').val(),
								organizer: jQuery('select[id^="mec_sf_organizer"]').val(),
								speaker: jQuery('select[id^="mec_sf_speaker"]').val(),
								tag: jQuery('select[id^="mec_sf_tag"]').val(),
								label: jQuery('select[id^="mec_sf_label"]').val(),
								cost_min: jQuery('input[id^="mec_sf_event_cost_min"]').val(),
								cost_max: jQuery('input[id^="mec_sf_event_cost_max"]').val(),
								display_label: '<?php echo $display_label; ?>',
								reason_for_cancellation: '<?php echo $reason_for_cancellation; ?>',
								is_category_page: '<?php echo $is_category_page; ?>',
								cat_id: '<?php echo $cat_id; ?>',
								local_time: '<?php echo $local_time; ?>',
								filter_category: '<?php echo $filter_category; ?>',
								filter_location: '<?php echo $filter_location; ?>',
								filter_organizer: '<?php echo $filter_organizer; ?>',
								filter_label: '<?php echo $filter_label; ?>',
								filter_tag: '<?php echo $filter_tag; ?>',
								filter_author: '<?php echo $filter_author; ?>',
							},
						});
						calendar.refetchEvents();
					}
				},
				<?php if (mec_general_calendar_find_event($this->sf_options, 'filter')) : ?>
				filterEvents: {
					text: '<?php echo esc_html__('Filter', 'modern-events-calendar-lite'); ?>',
					click: function() {
						jQuery(".mec-gCalendar-filters").fadeToggle( "fast", "linear" );
					}
				}
				<?php endif; ?>
			},
			<?php endif; ?>
			firstDay: <?php echo $week_start_day; ?>,
            headerToolbar: {
                left: 'title,prevYear,prev,today,next,nextYear',
                center: '',
				<?php if (mec_general_calendar_find_event($this->sf_options, 'find')) : ?>
                right: 'filterEvents,findEvents'
				<?php else : ?>
				right: ''
				<?php endif; ?>
            },
			eventDidMount: function(info) {
				var searchField = jQuery(".mec-gCalendar-search-text");
				if (searchField.length > 0) {
					var searchTerms = jQuery(".mec-gCalendar-search-text").val();
					if (searchTerms.length > 0){
						if (info.event._def.title.toLowerCase().indexOf(searchTerms) >= 0 || info.event._def.extendedProps.description.toLowerCase().indexOf(searchTerms) >= 0) {
							info.event.setProp('display','block')
						} else {
							info.event.setProp('display','none')
						}
					} else {
						info.event.setProp('display','block')
					}
				} else {
					info.event.setProp('display','block')
				}


				var backgroundColor = info.backgroundColor == '#' ? '#00acf8' : info.backgroundColor;
				var borderColor = info.borderColor == '#' ? '#00acf8' : info.borderColor;
    			jQuery(info.el).css("padding", "5px 3px");
    			jQuery(info.el).css("font-size", "12px");
    			jQuery(info.el).css("font-weight", "400");
    			jQuery(info.el).css("border-radius", "0");
    			jQuery(info.el).css("border-right", "none");
    			jQuery(info.el).css("border-top", "none");
    			jQuery(info.el).css("border-bottom", "none");
    			jQuery(info.el).css("border-left-width", "3px");
    			jQuery(info.el).css("background-color", "#fff");
    			jQuery(info.el).css("border-color", borderColor);
    			jQuery(info.el).css("white-space", 'normal');
    			jQuery(info.el).css("font-family", "-apple-system,BlinkMacSystemFont,\"Segoe UI\",Roboto,sans-serif");
    			// jQuery(info.el).css("z-index", "1");
    			jQuery(info.el).css("line-height", "19px");
    			jQuery(info.el).css("margin-top", "0");
    			jQuery(info.el).attr("target", "<?php echo $sed_method; ?>");
				<?php if ( $sed_method == 'no' ) : ?>
					jQuery(info.el).css({
					'cursor': 'default',
        			'pointer-events': 'none',
        			'text-decoration': 'none',
				});
				<?php endif; ?>
    			jQuery(info.el).attr("data-event-id", info.event._def.publicId);
    			jQuery(info.el).append('<span class="" style="background-color:' +  backgroundColor + ';position: absolute;top: 0;right: 0;bottom: 0;left: -1px;z-index: 0;opacity: .25;"></span>');
    			jQuery(info.el).append(info.event._def.extendedProps.reason_for_cancellation);
    			jQuery(info.el).append(info.event._def.extendedProps.locaTimeHtml);
    			jQuery(info.el).append(info.event._def.extendedProps.labels);

				<?php if ( $sed_method == 'm1') :  ?>
				jQuery(info.el).attr("rel", "noopener");
				jQuery('#mec_skin_<?php echo $this->id ;?>').mecGeneralCalendarView(
				{
					id: '<?php echo $this->id ;?>',
					atts: '<?php echo http_build_query(array('atts'=>$this->atts), '', '&') ;?>',
					ajax_url: '<?php echo admin_url('admin-ajax.php', NULL) ;?>',
					sed_method: '<?php echo $sed_method ;?>',
					image_popup: '<?php echo $this->image_popup ;?>',
				});
				<?php endif; ?>
				jQuery('.fc-daygrid-event-harness').mouseleave(function(e) {
					jQuery('.mec-gCalendar-tooltip').remove();
				});
			},
			eventMouseEnter: function(info) {
				var Image = info.event._def.extendedProps.image ? '<div class="mec-gCalendar-tooltip-image">' + info.event._def.extendedProps.gridsquare + '</div>' : '';

				var dateText = info.event._def.extendedProps.startDateStr != info.event._def.extendedProps.endDateStr  ? '<i class="mec-sl-calendar"></i><div><span class="mec-gCalendar-tooltip-date-start">' + info.event._def.extendedProps.start_date + '</span>' + '<span class="mec-gCalendar-tooltip-date-end">' + info.event._def.extendedProps.end_date + '</span></div>' : '<i class="mec-sl-calendar"></i><div><span class="mec-gCalendar-tooltip-date-start">' + info.event._def.extendedProps.start_date + '</span>' + '<span class="mec-gCalendar-tooltip-date-day">' + info.event._def.extendedProps.startDay + '</span></div>';

				var dateTime = '<i class="mec-sl-clock"></i><div><span class="mec-gCalendar-tooltip-time-start">' + info.event._def.extendedProps.start_time + '</span>' + '<span class="mec-gCalendar-tooltip-time-end">' + info.event._def.extendedProps.end_time + '</span></div>';

				var Location = info.event._def.extendedProps.location ? '<div class="mec-gCalendar-tooltip-location"><i class="mec-sl-location-pin"></i>' + info.event._def.extendedProps.location + '</div>' : '';

				var Title = '<div class="mec-gCalendar-tooltip-title"><a href="' +  info.event._def.url + '">' + info.event._def.title + '<span style="background:' + info.event._def.ui.backgroundColor + '"></span></a></div>';

				var tooltip = '<div class="mec-gCalendar-tooltip">' + Image +
				'<div class="mec-gCalendar-tooltip-date">' +
					'<div class="mec-gCalendar-tooltip-date-text">' + dateText + '</div>' +
					'<div class="mec-gCalendar-tooltip-date-time">' + dateTime + '</div>' +
			    '</div>' + Title + Location +
				'</div>';
				if ( jQuery(info.el).parent().find('.mec-gCalendar-tooltip').length < 1 ) jQuery(info.el).parent().append(tooltip);
			},
			dayMaxEvents: <?php echo $more_event; ?>,
			timeZone: <?php echo get_option('gmt_offset'); ?>,
			events: {
				url: '<?php echo get_rest_url(); ?>mec/v1/events',
				method: 'GET',
				startParam: 'startParam',
				endParam: 'endParam',
  				textColor: '#000',
				ajax: true,
				extraParams: {
					show_past_events: <?php echo $show_past_events; ?>,
					show_only_past_events: <?php echo $show_only_past_events; ?>,
					show_only_one_occurrence: <?php echo $show_only_one_occurrence; ?>,
					categories: (jQuery('select[id^="mec_sf_category"]').lenght > 0) ?  jQuery('select[id^="mec_sf_category"]').val() : '',
					multiCategories: (jQuery('.select2-hidden-accessible').lenght > 0) ? jQuery('.select2-hidden-accessible').val() : '',
					location: jQuery('select[id^="mec_sf_location"]').val(),
					organizer: jQuery('select[id^="mec_sf_organizer"]').val(),
					speaker: jQuery('select[id^="mec_sf_speaker"]').val(),
					tag: jQuery('select[id^="mec_sf_tag"]').val(),
					label: jQuery('select[id^="mec_sf_label"]').val(),
					cost_min: jQuery('input[id^="mec_sf_event_cost_min"]').val(),
					cost_max: jQuery('input[id^="mec_sf_event_cost_max"]').val(),
					display_label: '<?php echo $display_label; ?>',
					reason_for_cancellation: '<?php echo $reason_for_cancellation; ?>',
					is_category_page: '<?php echo $is_category_page; ?>',
					cat_id: '<?php echo $cat_id; ?>',
					local_time: '<?php echo $local_time; ?>',
					filter_category: '<?php echo $filter_category; ?>',
					filter_location: '<?php echo $filter_location; ?>',
					filter_organizer: '<?php echo $filter_organizer; ?>',
					filter_label: '<?php echo $filter_label; ?>',
					filter_tag: '<?php echo $filter_tag; ?>',
					filter_author: '<?php echo $filter_author; ?>',
				},
				failure: function() {
					alert('there was an error while fetching events!');
				},
			},
			forceEventDuration: true,
			loading: function(bool) {
				document.getElementById('gCalendar-loading').style.display =
				bool ? 'block' : 'none';
			},
		});
		calendar.render();

		const calendarHeaderFirstChild = jQuery('.fc-header-toolbar').find('.fc-toolbar-chunk h2');
		const calendarHeaderLastChild = jQuery('.fc-header-toolbar').find('.fc-toolbar-chunk:last-child');
		const calendarHeaderButton = calendarHeaderLastChild.find('.fc-button-group');

		// Search Bar Filter
		<?php if ($this->sf_options['text_search']['type'] != '0') : ?>
			jQuery( '<div class="mec-gCalendar-search-text-wrap"><i class="mec-sl-magnifier"></i><input type="text" class="mec-gCalendar-search-text" placeholder="<?php echo $this->sf_options['text_search']['placeholder'] ?  esc_html__($this->sf_options['text_search']['placeholder']) : esc_html('Search for events', 'modern-events-calendar-lite'); ?>" /></div>' ).insertBefore( ".fc-header-toolbar .fc-toolbar-chunk:last-child .fc-button-group" );

			jQuery('.mec-gCalendar-search-text').keypress(function(event){
				var keycode = (event.keyCode ? event.keyCode : event.which);
				if(keycode == '13'){
					jQuery('.fc-findEvents-button').trigger('click');
				}
			});
		<?php endif; ?>

		// Month Filter
		<?php if ($this->sf_options['month_filter']['type'] != '0') : ?>
		calendarHeaderFirstChild.append('<button class="gCalendarMonthFilterButton input-append date" id="gCalendarMonthFilterButton" data-date="12-02-2012" data-date-format="dd-mm-yyyy"><input id="mec-gCalendar-month-filter" class="span2" size="16" type="text" value="12-02-2012"><span class="openMonthFilter add-on"><i class="mec-sl-arrow-down"></i></span></button>');
		jQuery("#gCalendarMonthFilterButton").on('changeDate', function(ev) {
			var s = new Date(ev.date.valueOf());
			let ye = new Intl.DateTimeFormat('en', { year: 'numeric' }).format(s);
			let mo = new Intl.DateTimeFormat('en', { month: '2-digit' }).format(s);
			let da = new Intl.DateTimeFormat('en', { day: '2-digit' }).format(s);
			jQuery('#gCalendarMonthFilterButton').datepicker('hide');
			calendar.gotoDate(`${ye}-${mo}-${da}`)
		})
		setTimeout(function(){ jQuery(".datepicker").appendTo(".gCalendarMonthFilterButton"); }, 1000);
		<?php endif; ?>

		<?php if (mec_general_calendar_find_event($this->sf_options, 'filter') ) : ?>
		setTimeout(function(){ jQuery(".mec-gCalendar-filters").appendTo(calendarHeaderButton); }, 1000);
		jQuery('<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14.024" viewBox="0 0 14 14.024"><path id="Path_5991" data-name="Path 5991" d="M24.387,11H11.7a.654.654,0,0,0-.465,1.118l5.057,5.063v5.657a.654.654,0,0,0,.281.54l2.161,1.529a.659.659,0,0,0,1.032-.54V17.2l5.057-5.063A.654.654,0,0,0,24.387,11Z" transform="translate(-11.041 -11)" fill="#babfc2"/></svg>').appendTo("button.fc-filterEvents-button.fc-button.fc-button-primary");
		if ( jQuery('.mec-gCalendar-filters-wrap').length > 0 ) jQuery('.mec-gCalendar-filters-wrap .mec-dropdown-search').find('select').niceSelect();

		jQuery(document).on('click', function(e) {
			var button = jQuery(".fc-filterEvents-button");
			var wrap = jQuery(".mec-gCalendar-filters");
			if ((!button.is(e.target) && button.has(e.target).length === 0) && (!wrap.is(e.target) && wrap.has(e.target).length === 0)) {
				wrap.hide();
				if ( jQuery(".mec-searchbar-category-wrap select").length > 0 ) jQuery('.mec-searchbar-category-wrap select').select2('close');
			} else {
			}
		});
		<?php endif; ?>
	});
</script>