

<div id="skill_style">
	<div id="filter_genre">
            <p class="filter_title"> <span>Genres</span><span class="clear">Clear All</span></p>
            <ul id="genreUL" class="filter-tag-container">

            </ul>
    </div>        
    <div id = "hiddenGenres">
        <?php foreach ($skillGenres as $id => $genres) { ?>
            <div id = "genreSkillId<?= $id ?>">
                <?php foreach ($genres as $genre) { ?>
                    <p><span class="second-icon fui-checkbox-checked" data-genreId="<?= $genre['id'] ?>"></span><span><?= $genre['name'] ?></span></p>
                <?php } ?>					
            </div>
        <?php } ?>		
    </div>
    <div id="filter_influence">
        <p class="filter_title"> <span>Influences</span><span class="clear">Clear All</span></p>
        <ul id = "influenceUL" class="filter-tag-container">

        </ul>
    </div>
    <div id = "hiddenInfluences">
        <?php foreach ($skillInfluences as $id => $influences) { ?>
            <div id = "influenceSkillId<?= $id ?>">
                <?php foreach ($influences as $influence) { ?>
                    <p><span data-influenceId="<?= $influence['id'] ?>"></span><span><?= $influence['name'] ?></span></p>
                <?php } ?>					
            </div>
        <?php } ?>		
    </div>
</div>
        <button id = "searchByProjectNeed" type="button" class="fms-button inactive">Search</button>

    <p class="user-profile-title">Advanced Filters</p>

    <div class="slider-area">
        <div class="slider-container">
            <div id="slider-range"></div>
        </div>
        <div class="slider-tailer"></div>
        <div class="slider-start-value">0</div>
        <div class="slider-end-value">+45</div>
        <div id="slider_start_hint" class="slider-label">18</div>
        <div id="slider_end_hint" class="slider-label">32</div>
    </div>

    <div id="language" class="fms-select-container">
        <p>Choose a Language</p>
        <ul class="fms-select-options">
            <?php foreach ($languages as $row) { ?>
                <li><?= $row ?></li>
            <?php } ?>
        </ul>
    </div>

    <ul id="option_preview">
        <p>Gender:</p>
        <li><span class="radio checked"></span><span>Male<span></li>
        <li><span class="radio"></span>Female</li>
    </ul>

    <input id="country" name="country" type="text" autocomplete="off" placeholder="country" />

    <div id="city_wrap">
        <input id="city" type="text" placeholder="City or ZIP code" />
    </div>
    <button id="filter_result" type="button" class="fms-button inactive">Apply Filters</button>
</div>