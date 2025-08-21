<?php
/** @var yii\web\View $this */
?>



<div class="col-md-8">
    <div class="row right_cut_row">
        <div id="w0">
            <div style="border:1px dashed blue; width:96%;"></div>
            <div class="summary">Показані <b>1-3</b> із <b>3</b> записів.</div>

            <ul class="nav nav-tabs" role="tablist"><li class="active"><a href="/">Гарячі теми</a></li>
                <li><a href="/site/actualPolls">Актуальні для вас теми</a></li>
                <li><a href="/site/myPolls">Ваші опитування</a></li>
            </ul>    
            <div style="border:3px dashed green;">
                <h4>Category:hot</h4>
                <p>sort: desc</p>
                <p>limit: 10</p>
                <p>category: hot</p>
                <p>tag: </p>
                <p>period: month</p>
            </div>

            <div style="border:2px dashed red;">
                <h1>profile/index</h1>

                <p>
                    You may change the content of this page by modifying
                    the file <code><?= __FILE__; ?></code>.
                </p>              
            </div>

            <div style="border:2px dashed red;">
                <h4>#DEV01:category:[hot]</h4>
                <p>~/widgets/views/filters/_sort.php</p>
                <p>User:[-=-] isEmpty: 1 </p>
            </div>

            <div style="border:2px dashed red;">#DEV03:block03d [category == != profile]</div>
            <div class="sort_b">
                <span class="right_select_sort left_option_period">

                    <select onchange="document.location.href = & quot; /site/hotPolls / desc / & quot; + $(this).val() + & quot; /10?click=true&quot;">
                        <option value="day">за день</option>
                        <option value="week">за неділю</option>
                        <option value="month" selected="">за місяць</option>
                        <option value="year">за рік</option>
                    </select>
                </span>
                <span class="right_select_sort">
                    Сортування:
                    <select class="sort" onchange="document.location.href = & quot; /site/hotPolls / & quot; + $(this).val() + & quot; /month/10 & quot;">
                        <option value="desc" selected="">Рейтинг по спаданню</option>
                        <option value="asc">Рейтинг по зростанню</option>
                    </select>
                </span>
            </div>

            <div class="bottom_content_tabs">
                <div class="inner_banner_b">
                    <h2>banner_inner_blocks.png</h2>
                    <img src="/img/banner_inner_blocks.png" alt="">
                </div>
            </div>

            <!-- Modal DEV2404_M06 -->
            <div class="modal new_poll" id="new_poll0" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">
                                <span class="sr-only">Close</span>
                            </button>
                            <h4 class="modal-title">Нове Опитування</h4>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="/poll/createPoll">
                                <input name="Poll[id]" type="hidden" value="">

                                <div class="title_modal">Питання</div>
                                <div class="input_text_modal_b middle_text_input_b">
                                    <textarea name="Poll[title]" id="title"></textarea>
                                    <div class="count_symbols">
                                        Залишилось: 50 символів                        </div>
                                </div>
                                <div class="title_modal">Опис <span>(не обовязково)</span></div>
                                <div class="input_text_modal_b middle_text_input_b">
                                    <textarea name="Poll[describe]"></textarea>
                                </div>

                                <div class="title_modal">Варіанти відповіді</div>
                                <div class="item_variants">
                                    <span>1</span>
                                    <input name="Poll[options][]" style="margin-left:10px;" class="variant_text answer_var" maxlength="60" type="text" value="">
                                    <div class="count_symbols">
                                        Залишилось: <div class="answer_left">60</div> символів                        </div>
                                    <a href="#" class="del_btn" data-id="0"></a>
                                </div>
                                <div class="item_variants">
                                    <span>2</span>
                                    <input name="Poll[options][]" style="margin-left:10px;" class="variant_text answer_var" maxlength="60" type="text" value="">
                                    <div class="count_symbols">
                                        Залишилось: <div class="answer_left">60</div> символів                        </div>
                                    <a href="#" class="del_btn" data-id="0"></a>
                                </div>
                                <div class="item_variants">
                                    <span>3</span>
                                    <input name="Poll[options][]" style="margin-left:10px;" class="variant_text answer_var" maxlength="60" type="text" value="">
                                    <div class="count_symbols">
                                        Залишилось: <div class="answer_left">60</div> символів                        </div>
                                    <a href="#" class="del_btn" data-id="0"></a>
                                </div>
                                <div class="item_variants">
                                    <span>4</span>
                                    <input name="Poll[options][]" style="margin-left:10px;" class="variant_text answer_var" maxlength="60" type="text" value="">
                                    <div class="count_symbols">
                                        Залишилось: <div class="answer_left">60</div> символів                        </div>
                                    <a href="#" class="del_btn" data-id="0"></a>
                                </div>
                                <a href="#" class="create_new_poll my_profile modal_add" data-id="0">Додати варіант</a>
                            </form></div>
                        <div class="modal-body">
                            <div class="title_modal">Теги</div>
                            <div class="sub_title_modal">Введіть теги для опитування, через кому.</div>
                            <div class="input_text_modal_b middle_text_input_b">
                                <textarea name="Poll[tags]"></textarea>
                            </div>
                        </div>
                        <div class="modal-body">
                            <div class="title_modal">Відображення результатів</div>
                            <div class="sub_title_modal">Оберіть тип графіка для показу результатів голосування:</div>
                            <div class="white_b_graphs">
                                <div class="tabs_graphs">
                                    <ul class="nav nav-tabs" role="tablist">
                                        <li class="active"><input id="type_1" type="radio" name="Poll[type]" value="1" checked="" hidden=""><a href="#horizontal_b_chart" class="horizontal_b_chart" role="tab" data-toggle="tab"></a></li>
                                        <li class=""><input id="type_2" type="radio" name="Poll[type]" value="2" hidden=""><a href="#vertical_b_chart" class="vertical_b_chart" role="tab" data-toggle="tab"></a></li>
                                        <li class=""><input id="type_3" type="radio" name="Poll[type]" value="3" hidden=""><a href="#pie_chart" class="pie_chart" role="tab" data-toggle="tab"></a></li>
                                    </ul>
                                </div>
                                <div class="tab-content tab_visual">
                                    <div id="horizontal_b_chart" class="tab-pane active" style="width:500px; height:400px;"></div>
                                    <div id="vertical_b_chart" class="tab-pane " style="width:500px; height:400px;"></div>
                                    <div id="pie_chart" class="tab-pane " style="width:500px; height:400px;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <a href="#" class="my_profile modal_add next_modal_btn" data-dismiss="modal" data-target="#new_poll_next_step0" data-toggle="modal">ДАЛІ</a>
                            <div><a href="#" class="create_new_poll newPollCancel" data-dismiss="modal">Скасувати</a></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal DEV2404_M07 -->
            <div class="modal new_poll poll" id="new_poll_next_step0" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <h2>class="modal new_poll poll" id="new_poll_next_step0"</h2>
                    </div>
                </div>
            </div>









            <div class="bottom_pagination_b clearfix">
                <div style="border:2px dashed red;">#DEV03:block04 [~/frontend/widgets/views/poll-list-soter.php]</div>
                <div class="right_count_select">
                    Опитувань на сторінку:
                    <select class="count_article" onchange="document.location.href = & quot; /site/hotPolls / desc / month / & quot; + $(this).val() + & quot; ?click = true & quot;">
                        <option value="10" selected="">10</option>
                        <option value="5">5</option>
                        <option value="2">2</option>
                    </select>
                </div>



            </div>
        </div>
    </div>
</div>