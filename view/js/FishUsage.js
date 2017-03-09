FishData = {
	draughts : null,

	reportDone : false,

	specialReport : {
		fishers : {

		},
		fishes : {

		},
		gears : {

		}, 
		places : {

		}
	},

	maxLimit: null,
	/**
	* This function calculates the special report by faceted selection
	*
	*/
	calculateSpecialReport : function() {
		$.each(FishData.draughts.Draughts, function(i, Draught) {
			if(FishData.specialReport.fishers[Draught.Fisher.Name] == undefined) {
				FishData.specialReport.fishers[Draught.Fisher.Name] = [];
			}
			if(FishData.specialReport.fishes[Draught.Fish.Name] == undefined) {
				FishData.specialReport.fishes[Draught.Fish.Name] = [];
			}
			if(FishData.specialReport.gears[Draught.Gear.Name] == undefined) {
				FishData.specialReport.gears[Draught.Gear.Name] = [];
			}
			if(FishData.specialReport.places[Draught.Place.Name] == undefined) {
				FishData.specialReport.places[Draught.Place.Name] = [];
			}
			FishData.specialReport.fishes[Draught.Fish.Name].push(Draught.Weight);
			FishData.specialReport.fishers[Draught.Fisher.Name].push(Draught.Weight);
			FishData.specialReport.gears[Draught.Gear.Name].push(Draught.Weight);
			FishData.specialReport.places[Draught.Place.Name].push(Draught.Weight);
		});

		this.formatSpecialReport();

		this.reportDone = true;
	},
	/**
	<a href="/fishes/fish/<?php echo $draught->getDraughtId();?>/">
	<div class="row">
		<div class="col-sm-2 text-center col-xs-4">
			<?php echo $draught->getDate()->format("d.m.Y"); ?>
		</div>
		<div class="col-sm-2 text-center col-xs-4">
			<?php echo $draught->getFish()->getName(); ?>
		</div>
		<div class="col-sm-2 text-center hidden-xs">
			<?php echo $draught->getFisher()->getName(); ?>
		</div>
		<div class="col-sm-2 text-center hidden-xs">
			<?php echo $draught->getGear()->getName(); ?>
		</div>
		<div class="col-sm-2 text-center hidden-xs">
			<?php echo $draught->getPlace()->getName(); ?>
		</div>
		<div class="col-sm-2 text-center col-xs-4">
			<?php echo number_format($draught->getWeight() / 1000, 2); ?>
		</div>
	</div>
</a>*/
	/**
	* Horrible monster function that wants to build all shit after the first 24 is printed
	* This saves some bandwidth. From 570kb doc to 110kb
	*/
	buildDataResult : function() {
		var classes = ['col-sm-2 text-center col-xs-4', 
			'col-sm-2 text-center col-xs-4', 
			'col-sm-2 text-center hidden-xs',
			'col-sm-2 text-center hidden-xs', 
			'col-sm-2 text-center hidden-xs',
			'col-sm-2 text-center col-xs-4'
		];

		var objectsToCall = ['Date', 
			'Fish.Name', 
			'Fisher.Name', 
			'Gear.Name',
			'Place.Name',
			'Weight'
		];

		var dateFormat = "d.m.Y";
		var weightDecimals = 2;

		for(var a = FishData.maxLimit; a < FishData.draughts.Draughts.length; a++) {
			var draught = FishData.draughts.Draughts[a];
			var row = $("<a href='/fishes/fish/" + draught.DraughtId + "'/></a>");
			$(row).append("<div class='row'></div>");
			var rowToset = $(row).find('.row');

			$.each(classes, function(i, classSelector) {
				var dataObject = null;
				var objectToCall = objectsToCall[i];
				var namespaces = objectToCall.split('.');

				if(namespaces != null && namespaces.length > 1) {
					dataObject = draught[namespaces[0]][namespaces[1]];
				} else {
					dataObject = draught[objectToCall];
				}

				if(objectToCall == 'Date') {
					var date = new Date(dataObject);
					dataObject = date.getDate() < 10 ? "0" + date.getDate() : date.getDate();
					dataObject += ".";
					dataObject += (date.getMonth() + 1) < 10 ? "0" + (date.getMonth() + 1) : (date.getMonth() + 1);
					dataObject += ".";
					dataObject += (date.getYear() + 1900);
				} else if(objectToCall == 'Weight') {
					dataObject = (dataObject / 1000).toFixed(2);
				}

				$(rowToset).append('<div class="' + classSelector + '">' + dataObject + '</div>');
			});

			$("#result-footer").before(row);
		};
	},

	formatSpecialReport : function() {

		if(!this.reportDone) {
			$.each(FishData.specialReport, function(key, value) {
				var keys = Object.keys(value);
				var len = keys.length;

				keys.sort();

				$.each(keys, function(i, name) {
					var weights = value[name];
					var total = 0;
					var count = weights.length;
					var min = null;
					var max = null;
					var avg = null;

					$.each(weights, function(index, weight) {
						if(min == null || min > weight) {
							min = weight;
						}
						if(max == null || max < weight) {
							max = weight;
						}

						total += weight;

					});


					if(count != 0) {
						avg = (total / count).toFixed(3);
					}

					$("#report-modal ." + key).append("<div class='row'><span class='title'>" + name + "</span><span class='count'>" + count + "</span><span class='min'>" + min + "</span><span class='max'>" + max + "</span><span class='avg'>" + avg + "</span></div>");
				});
			});

			setTimeout(function() {

				$.each(['.title', '.count', '.min', '.max', '.avg'], function(i, value) {
					var maxWidth = 0;

					$.each($("#report-modal " + value), function(j, element) {
						var elementWidth = $(element).width();

						if(maxWidth < elementWidth) {
							maxWidth = elementWidth;
						}
					});

					$.each($("#report-modal " + value), function(j, element) {
						$(element).width(maxWidth);
					});
				});

			}, 400);

		}

		$("#report-modal").modal("show");
	}
};