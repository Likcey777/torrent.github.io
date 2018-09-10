<article class="section cat-cont actor-cont">
    <center>
        <header class="darken site-desc top-desc clearfix img-bg">
            <h1>Фильмы с {title}</h1>
            <div class="act">
                <p>Фильмография {title}, все новые фильмы с его участием!</p>
            </div>
        </header>
    </center>
    <div class="cat-cols clearfix">

        <div class="cat-side">
            <div class="actor-wrap">
                <div class="act-name lighter" itemscope itemtype="http://schema.org/Person">
                    <center><span itemprop="name">{title}</span></center>
                </div>
                <div class="act-face">
                    <img src="{image-1}" alt="{title}" />
                </div>

            </div>
            <div class="top">
                <div class="top-t"><span class="fa fa-list"></span>Фильмы списком</div>
                <div class="top-c films-list">
                    {include file="/engine/modules/connect_person_movie_list.php?post_id={news-id}&list=movie&person_type=2&template=connect_person_movie_movie&limit=10"}
                </div>
            </div>
        </div>

        <div class="section-items floaters clearfix cat-items">
            {include file="/engine/modules/connect_person_movie_list.php?post_id={news-id}&list=movie&person_type=2&template=&template=connect_person_movie_movie2&limit=10"}
        </div>

    </div>

</article>