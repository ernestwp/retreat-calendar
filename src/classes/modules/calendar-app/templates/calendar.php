<div class="retreat-calendar">

	<div id="retreat-calendar--col-details" class="retreat-calendar--col retreat-calendar--col-left retreat-calendar--col-occupied">
		<div class="retreat-calendar--col-content">
			<h1 class="retreat-calendar--heading retreat-calendar--heading-left">
				<select id="retreat-calendar-guest-name" type="text" value="" placeholder="meal preference" title="meal preference">
					<option value="0">Select Guest</option>
				</select>
			</h1>
			<div class="retreat-calendar--registration-details">

				<ul class="retreat-calendar--view-details">
					<li>Status: <span id="retreat-calendar-status"></span></li>
					<li>Check-in: <span id="retreat-calendar-from"></span></li>
					<li>Check-out: <span id="retreat-calendar-to"></span></li>
					<li>Additional Information:</li>
				</ul>

				<p class="retreat-calendar--add-details">
					<input id="retreat-calendar-flight-info" type="text" value="" placeholder="Flight information" title="Flight information"/>
					<span class="retreat-calendar--add-details-saved">&#x2714;</span>
				</p>

				<p class="retreat-calendar--add-details">
					<select id="retreat-calendar-select-diet" type="text" value="" placeholder="meal preference" title="meal preference">
						<option value="none">Select Diet</option>
						<option value="omnivore">Omnivore</option>
						<option value="vegetarian">Vegetarian</option>
						<option value="vegan">Vegan</option>
					</select>
					<span class="retreat-calendar--add-details-saved">&#x2714;</span>
				</p>

				<p class="retreat-calendar--add-details">
					<label>Add yoga class<input id="retreat-calendar-add-yoga" type="checkbox" value="yoga-class"/></label>
					<span class="retreat-calendar--add-details-saved">&#x2714;</span>
				</p>

				<p class="retreat-calendar--add-details">
					<label>Add juice detox<input id="retreat-calendar-add-juice" type="checkbox" value="juice-detox"/></label>
					<span class="retreat-calendar--add-details-saved">&#x2714;</span>
				</p>

				<p class="retreat-calendar--add-details">
					<label>Add massage<input id="retreat-calendar-add-massage" type="checkbox" value="massage"/></label>
					<span class="retreat-calendar--add-details-saved">&#x2714;</span>
				</p>

				<p class="retreat-calendar--add-details">
					<label>Add breath-work session<input id="retreat-calendar-add-breath"  type="checkbox" value="breath-work-session"/></label>
					<span class="retreat-calendar--add-details-saved">&#x2714;</span>
				</p>

			</div>
		</div>
	</div>

	<div class="retreat-calendar--col retreat-calendar--col-right">
		<div class="retreat-calendar--col-content">
			<h1 class="retreat-calendar--heading retreat-calendar--heading-right">2025</h1>
			<ul class="months">
				<?php foreach ( $this->months as $key => $month ): ?>
					<li class="<?php echo ( 8 === $key ) ? 'selected' : ''; ?>">
						<a href="#" data-value="<?php echo $key; ?>">
							<?php echo $month; ?>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
			<ul class="weekday">
				<?php foreach ( $this->days as $key => $day ): ?>
					<li><a href="#" data-value="<?php echo $key; ?>"><?php echo $day; ?></a></li>
				<?php endforeach; ?>
			</ul>
			<ul class="days">
				<?php foreach ( $this->dates_grid as $day ): ?>
					<li><a href="#"
						   title="M Beatty, E Smuga"
						   data-day="<?php echo ( ! empty( $day->date ) ) ? $day->date : ''; ?>">
							<?php echo ( ! empty( $day->num ) ) ? $day->num : ''; ?>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
			<h1 class="retreat-calendar--heading retreat-calendar--heading-right-bottom">Room 5<span id="retreat-calendar-days-available"></span></h1>
		</div>
	</div>

	<div class="clearfix"></div>

</div>