<!-- Client Version -->
<div id="surstudio_plugin_translator_revolution_dropdown_settings">

    <form method="post" enctype="multipart/form-data" action="" name="surstudio_admin" id="surstudio_admin">

        <div class="surstudio_plugin_translator_revolution_dropdown_page_title_container">
            <div class="surstudio_plugin_translator_revolution_dropdown_da_icon_main">
                <div class="dashicons-before dashicons-translation"></div>
            </div>
            <h2 class="surstudio_plugin_translator_revolution_dropdown_page_title">Translator Revolution DropDown</h2>
        </div>







        {{ cache_folder_validate.false:begin }}
        <div class="surstudio_plugin_translator_revolution_dropdown_cache_folder_validate surstudio_plugin_translator_revolution_dropdown_message">
            <table border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td class="surstudio_plugin_translator_revolution_dropdown_da_icon_container">
                        <div class="dashicons-before dashicons-warning"></div>
                    </td>
                    <td><p>{{ general_cache_folder_validate_message }}</p></td>
                </tr>
            </table>
        </div>
        {{ cache_folder_validate.false:end }}

        {{ cache_file_validate.false:begin }}
        <div class="surstudio_plugin_translator_revolution_dropdown_cache_file_validate surstudio_plugin_translator_revolution_dropdown_message">
            <table border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td class="surstudio_plugin_translator_revolution_dropdown_da_icon_container">
                        <div class="dashicons-before dashicons-warning"></div>
                    </td>
                    <td><p>{{ general_cache_file_validate_message }}</p></td>
                </tr>
            </table>
        </div>
        {{ cache_file_validate.false:end }}

        {{ fsockopen_validate.false:begin }}
        <div class="surstudio_plugin_translator_revolution_dropdown_fsockopen_validate surstudio_plugin_translator_revolution_dropdown_message">
            <table border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td class="surstudio_plugin_translator_revolution_dropdown_da_icon_container">
                        <div class="dashicons-before dashicons-no"></div>
                    </td>
                    <td><p>{{ general_fsockopen_validate_message }}</p></td>
                </tr>
            </table>
        </div>
        {{ fsockopen_validate.false:end }}

        {{ ssl_validate.false:begin }}
        <div class="surstudio_plugin_translator_revolution_dropdown_ssl_validate surstudio_plugin_translator_revolution_dropdown_message">
            <table border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td class="surstudio_plugin_translator_revolution_dropdown_da_icon_container">
                        <div class="dashicons-before dashicons-no"></div>
                    </td>
                    <td><p>{{ general_openssl_validate_message }}</p></td>
                </tr>
            </table>
        </div>
        {{ ssl_validate.false:end }}

        {{ just_saved.true:begin }}
        <div class="surstudio_plugin_translator_revolution_dropdown_saved surstudio_plugin_translator_revolution_dropdown_message">
            <table border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td class="surstudio_plugin_translator_revolution_dropdown_da_icon_container">
                        <div class="dashicons-before dashicons-yes"></div>
                    </td>
                    <td><p>{{ saved_message }}</p></td>
                </tr>
            </table>
        </div>
        <script type="text/javascript">
            SurStudioPluginTranslatorRevolutionDropDownAdmin.hideMessage(".surstudio_plugin_translator_revolution_dropdown_saved", 1000);
        </script>
        {{ just_saved.true:end }}





        {{ just_reseted.true:begin }}
        <div class="surstudio_plugin_translator_revolution_dropdown_reseted surstudio_plugin_translator_revolution_dropdown_message">
            <table border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td class="surstudio_plugin_translator_revolution_dropdown_da_icon_container">
                        <div class="dashicons-before dashicons-yes"></div>
                    </td>
                    <td><p>{{ reseted_message }}</p></td>
                </tr>
            </table>
        </div>
        <script type="text/javascript">
            SurStudioPluginTranslatorRevolutionDropDownAdmin.hideMessage(".surstudio_plugin_translator_revolution_dropdown_reseted", 1000);
        </script>
        {{ just_reseted.true:end }}

        <div class="surstudio_plugin_translator_revolution_dropdown_admin_container">




            <!-- tab -->
            <div class="surstudio_plugin_translator_revolution_dropdown_ui_tabs_container" id="surstudio_plugin_translator_revolution_dropdown_main_navigation">
                <ul>

                    <li class="surstudio_plugin_translator_revolution_dropdown_ui_tab surstudio_plugin_translator_revolution_dropdown_ui_tab_selected" id="translations_menu">
                        <div class="dashicons-before dashicons-index-card"></div>
                        <span><span>{{ translations_message }}</span></span>
                    </li>

                </ul>
            </div>


            <!-- content -->
            <div class="surstudio_plugin_translator_revolution_dropdown_main_form_container">

                <div class="surstudio_plugin_translator_revolution_dropdown_ui_tabs_main_container">

                    <div class="surstudio_plugin_translator_revolution_dropdown_ui_tab_container surstudio_plugin_translator_revolution_dropdown_display" id="translations_tab">
                        <div class="surstudio_plugin_translator_revolution_dropdown_ui_tab_content">

                            <div class="surstudio_plugin_translator_revolution_dropdown_ui_tabs_container surstudio_plugin_translator_revolution_dropdown_ui_tabs_container_alt">
                                <ul>
                                    <li class="surstudio_plugin_translator_revolution_dropdown_ui_tab surstudio_plugin_translator_revolution_dropdown_ui_tab_selected" id="translations_general_menu"><div class="dashicons-before dashicons-list-view"></div><span><span>{{ translations_general_message }}</span></span></li>

                                </ul>
                            </div>

                            <div class="surstudio_plugin_translator_revolution_dropdown_main_form_container">

                                <div class="surstudio_plugin_translator_revolution_dropdown_ui_tabs_main_container">

                                    <div class="surstudio_plugin_translator_revolution_dropdown_ui_tab_container surstudio_plugin_translator_revolution_dropdown_{{ translations_general.show.false:begin }}no_{{ translations_general.show.false:end }}display" id="translations_general_tab">

                                        <div class="surstudio_plugin_translator_revolution_dropdown_ui_tab_content">

                                            {{ group_4 }}

                                        </div>

                                    </div>

                                    <div class="surstudio_plugin_translator_revolution_dropdown_ui_tab_container surstudio_plugin_translator_revolution_dropdown_{{ translations_import.show.false:begin }}no_{{ translations_import.show.false:end }}display" id="translations_import_tab">

                                        <div class="surstudio_plugin_translator_revolution_dropdown_ui_tab_content">

                                            {{ group_6 }}

                                        </div>

                                    </div>

                                    <div class="surstudio_plugin_translator_revolution_dropdown_ui_tab_container surstudio_plugin_translator_revolution_dropdown_{{ translations_export.show.false:begin }}no_{{ translations_export.show.false:end }}display" id="translations_export_tab">

                                        <div class="surstudio_plugin_translator_revolution_dropdown_ui_tab_content">

                                            {{ group_7 }}

                                        </div>

                                    </div>

                                    <div class="surstudio_plugin_translator_revolution_dropdown_ui_tab_container surstudio_plugin_translator_revolution_dropdown_{{ translations_permissions.show.false:begin }}no_{{ translations_permissions.show.false:end }}display" id="translations_permissions_tab">

                                        <div class="surstudio_plugin_translator_revolution_dropdown_ui_tab_content">

                                            {{ group_9 }}

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>
                    </div>


                </div>

            </div>

        </div>

    </form>

</div>

<script type="text/javascript">
    /*<![CDATA[*/
    SurStudioPluginTranslatorRevolutionDropDownAdmin.initialize("{{ ajax_url }}");
    /*]]>*/
</script>
