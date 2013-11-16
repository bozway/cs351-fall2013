
<div id="tourdates_container" class="tour">
    <h5>Tour Info</h5>
    <div class="tour_info_msg">Hello User. Here goes the information about your Tours !!!</div><hr/>
    <?php foreach ($tours as $row) { ?>
        <div class="tour_block">
            <div class="tour_schedule">
                <div class="tour_date">
                    <div class="tour_date_style"><?= $row['day'] ?></div>
                    <div class="tour_month_style"> <?= strtoupper($row['month']); ?> </div>
                    <div class="tour_day_style"> <?= ucfirst($row['week_day']); ?> </div>
                </div>
                <div class="tour_venue">
                    <div class="tour_location"><?= $row['location'] ?></div>
                    <div class="tour_loc_city"><?= $row['city'] ?></div>
                    <div class="tour_indi_info"><?= $row['info'] ?></div>
                </div>
            </div>

            <div class="tour_tickets">
                <input class="tour_tickets_share" type="button" value="Going? Share it!">
                <input class="tour_tickets_find" type="button" value="Find Tickets">
            </div>
        </div>
        <hr/>
    <?php } ?>
</div>

