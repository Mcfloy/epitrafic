<div class="hidden-xs">
	<div role="tabpanel">
		<ul class="nav nav-tabs">
			<li role="presentation" class="active"><a href="#information" aria-controls="information" role="tab" data-toggle="tab">Informations</a></li>
			<li role="presentation"><a href="#planning" aria-controls="planning" role="tab" data-toggle="tab">Planning</a></li>
			<li role="presentation"><a href="#projet" aria-controls="projet" role="tab" data-toggle="tab">10 projets en cours</a></li>
			<li role="presentation"><a href="#notification" aria-controls="notification" role="tab" data-toggle="tab">42 notifications</a></li>
		</ul>

		<div class="tab-content">
			<div role="tabpanel" class="tab-pane fade in active" id="information">
				<?php
					include('marvin_information.php');
				?>
			</div>
			<div role="tabpanel" class="tab-pane fade" id="planning">
				<?php
					include('marvin_planning.php');
				?>
			</div>
			<div role="tabpanel" class="tab-pane fade" id="projet">
				<?php
					include('marvin_projet.php');
				?>
			</div>
			<div role="tabpanel" class="tab-pane fade" id="notification">
				<?php
					include('marvin_notification.php');
				?>
			</div>
		</div>
	</div>
</div>