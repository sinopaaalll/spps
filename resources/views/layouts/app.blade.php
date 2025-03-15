<!DOCTYPE html>
<html lang="en">
<!-- [Head] start -->

<x-Admin.Head />
<!-- [Head] end -->

<!-- [Body] Start -->

<body>
    <!-- [ Pre-loader ] start -->
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>
    <!-- [ Pre-loader ] End -->

    <!-- [ Sidebar Menu ] start -->
    <x-Admin.Sidebar />
    <!-- [ Sidebar Menu ] end -->

    <!-- [ Header Topbar ] start -->
    <x-Admin.Topbar />
    <!-- [ Header ] end -->

    <!-- [ Main Content ] start -->
    <div class="pc-container">
        <div class="pc-content">
            <!-- [ breadcrumb ] start -->
            <x-Admin.Breadcrumb />
            <!-- [ breadcrumb ] end -->


            <!-- [ Main Content ] start -->
            @yield('content')
            <!-- [ Main Content ] end -->
        </div>
    </div>
    <!-- [ Main Content ] end -->
    <x-Admin.Footer />

    <!-- Required Js -->
    <x-Admin.Script />


</body>
<!-- [Body] end -->

</html>
