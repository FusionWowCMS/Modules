<section id="update_online_module">
    <div style="text-align:center;margin-top:10px;margin-bottom:10px;">
        <img src="{$image_path}ajax.gif"/>
    </div>
</section>

<script type="text/javascript">
    var OnlineModule = {
        field: $("#update_online_module"),

        /**
         * Refresh the realm status
         */
        update: function () {
            $.get(Config.URL + "goldtodp/Load", function (data) {
                OnlineModule.field.fadeOut(300, function () {
                    OnlineModule.field.html(data);
                    OnlineModule.field.fadeIn(300, function () {
                        Tooltip.refresh();
                    });
                })
            });
        }
    }

    OnlineModule.update();
</script>