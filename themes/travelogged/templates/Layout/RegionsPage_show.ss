<% include Banner %>
<!-- BEGIN CONTENT -->
<div class="content">
    <div class="container">
        <div class="row">
            <div class="main col-sm-8">
                <% with $Region %>
                    <div class="blog-main-image">
                        $Photo.Fit(720,255)         <!--$Photo.SetWidth(750)-->
                    </div>
                    $Description
                <% end_with %>
            </div>

            <div class="sidebar gray col-sm-4">
                <h2 class="section-title">Regions</h2>
                <ul class="categories subnav">
                    <!--Lesson:14 Remember that, even though the content is all driven by the Region object,
                        we're still in the scope of RegionsPageController, so we step into <%--<% loop $Regions %> --%>to get
                        that has_many relation that we also use on list view -->
                    <% loop $Regions %>
                        <li class="$LinkingMode"><a class="$LinkingMode" href="$Link">$Title</a></li>
                    <% end_loop %>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- END CONTENT -->