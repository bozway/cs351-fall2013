<div id="search_result" class="search_result_container">
    <div class="search_count_sort">
        <div id="result_count"></div>
        <span class="sort">Sort By</span>
        <div class="search_sort">
            <div id="search_musician_sort" class="fms-select-container" data-select-inactive="1">
                <p></p>
                <ul class="fms-select-options">
                    <li data-sort="match">Match %</li>
                    <li data-sort="lastactive">Last Active</li>
                    <li data-sort="lastname">Last Name</li>
                    <li data-sort="firstname">First Name</li>
                    <li data-sort="experience">Project Experience</li>
                </ul>
            </div>
        </div>
    </div>

    <ul id="musician-search-result">
        <?php for ($i = 0; $i < count($result); $i++) { ?>
            <li>
                <div class="project_info">
                    <div class="project_content">
                        <label class="project_play">
                            <img src="<?php
                            if (isset($result[$i]['cover_pic'])) {
                                echo $result[$i]['cover_pic'];
                            }
                            ?>"/>
                            <div class="project-photo-hover"></div>
                            <audio id="<?= "audio" + $i; ?>" height="100" width="100">
                                <source src="<?php
                                if (isset($result[$i]['audio_url'])) {
                                    echo $result[$i]['audio_url'];
                                }
                                ?>" type="audio/mp3">
                            </audio>
                        </label>
                        <div class="project_detail_info">
                            <div id="<?= $result[$i]['user_id']; ?>" class="project_title">
                                <?php
                                if (isset($result[$i]['name'])) {
                                    echo $result[$i]['name'];
                                }
                                ?></div>
                            <div class="project_location"><?php
                            if (isset($result[$i]['location'])) {
                                echo $result[$i]['location'];
                            }
                            ?></div>
                            <div class="project_msg_audition">
                                <button class="message" data-btn="msg" data-userid="<?= $result[$i]['user_id']; ?>" ></button>
                                <button class="btn btn-primary save_musician" data-toggle="button" data-btn="con" data-userid="<?= $result[$i]['user_id']; ?>">Save</button>
                            </div>
                            <div class="save_message">
                                <p>Last Active <?php
                                if (isset($result[$i]['last_active'])) {
                                    echo $result[$i]['last_active'];
                                }
                                ?></p>
                                <p>Worked on <?php
                                if (isset($result[$i]['num_projects'])) {
                                    echo $result[$i]['num_projects'];
                                }
                                ?> Projects</p>
                            </div>
                        </div>
                    </div>
                    <div class="project_details">
                        <div class="match_rating">match
                            <div class="rating_value"><?php
                            if (isset($result[$i]['match'])) {
                                echo $result[$i]['match'];
                            }
                            ?>%</div>
                        </div>
                        <button class="view_profile" data-url="<?= $result[$i]['profile_url'] ?>">View Profile</button>
                    </div>
                </div><hr/>
            </li>
        <?php } ?>
    </ul> 

    <div class="pagination">
        <div class="paginate">
            <ul class="fms_pager">
                <?php $num_pages = count($result) / 5;
                    for ($j = 0; $j < $num_pages; $j++) { ?>
                <li class="fms_pager_tile fms_pager_currnent"><?= $j + 1; ?></li>
                <?php } ?>
            </ul>
        </div>
        <button class="back_to_top">BACK TO TOP</button>
    </div>
</div>
