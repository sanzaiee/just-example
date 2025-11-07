<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('admin.dashboard') }}" class="app-brand-link">
            <span class="app-brand-text demo menu-text fw-bold">{{ get_site_name() }}</span>
        </a>
        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="ti menu-toggle-icon d-none d-xl-block ti-sm align-middle"></i>
            <i class="ti ti-x d-block d-xl-none ti-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <li class="menu-item {{ request()->is('admin') ? 'active' : '' }}">
            <a href="{{ route('admin.dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-smart-home"></i>
                <div data-i18n="dashboard">Dashboard</div>
            </a>
        </li>

        <li class="menu-item {{ request()->is('admin/tag*') ? 'active' : '' }}">
            <a href="{{ route('tag.index') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-category"></i>
                <div data-i18n="tag">Tag</div>
            </a>
        </li>

        <li class="menu-item {{ request()->is('admin/category*') ? 'active' : '' }}">
            <a href="{{ route('category.index') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-category"></i>
                <div data-i18n="category">Categories</div>
            </a>
        </li>

        <li class="menu-item {{ request()->is('admin/product*') ? 'active' : '' }}">
            <a href="{{ route('product.index') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-category"></i>
                <div data-i18n="product">Products</div>
            </a>
        </li>

         <li class="menu-item {{ request()->is('admin/shipping-address*') ? 'active' : '' }}">
            <a href="{{ route('shipping.address') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-location"></i>
                <div data-i18n="product">Shipping Addresses</div>
            </a>
        </li>

        {{-- <li class="menu-item {{ request()->is('admin/blog*') ? 'active' : '' }}">
            <a href="{{ route('blog.index') }}" class="menu-link">
                <i class="menu-icon tf-icons fa-sharp fa-solid fa-blog"></i>
                <div data-i18n="blog">Blog</div>
            </a>
        </li>

        <li class="menu-item {{ request()->is('admin/banner*') ? 'active' : '' }}">
            <a href="{{ route('banner.index') }}" class="menu-link">
                <i class="menu-icon tf-icons fa-regular fa-images"></i>
                <div data-i18n="banner">Banners</div>
            </a>
        </li>

        <li class="menu-item {{ request()->is('admin/faq*') ? 'active' : '' }}">
            <a href="{{ route('faq.index') }}" class="menu-link">
                <i class="menu-icon tf-icons fa-solid fa-question"></i>
                <div data-i18n="faq">FAQs</div>
            </a>
        </li>

        <li class="menu-item {{ request()->is('admin/testimonial*') ? 'active' : '' }}">
            <a href="{{ route('testimonial.index') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-users"></i>
                <div data-i18n="testimonial">Testimonial</div>
            </a>
        </li>

        <li class="menu-item {{ request()->is('admin/gallery*') ? 'active' : '' }}">
            <a href="{{ route('gallery.index') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-color-swatch"></i>
                <div data-i18n="gallery">Gallery</div>
            </a>
        </li>


        <li class="menu-item {{ request()->is('admin/team*') ? 'active' : '' }}">
            <a href="{{ route('team.index') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-users"></i>
                <div data-i18n="our team">Our Team</div>
            </a>
        </li>

        <li class="menu-item {{ (request()->is('admin/page*') ||request()->is('admin/sub-pages*')) ? 'active' : '' }}">
            <a href="{{ route('page.index') }}" class="menu-link">
                <i class="menu-icon tf-icons ti-slideshow"></i>
                <div data-i18n="our page">Our Pages</div>
            </a>
        </li> --}}

        {{-- <li class="menu-item {{ request()->is('admin/member*') ? 'active open' : '' }}">
             <a href="javascript:void(0)" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-users"></i>
                <div>Members</div>
             </a>
             <ul class="menu-sub">
                   <li class="menu-item  {{ request()->is('admin/member-import*') ? 'active' : '' }}">
                         <a href="{{ route('import.member') }}" class="menu-link">
                           <div>Import Member</div>
                         </a>
                   </li>
                   <li class="menu-item {{ request()->is('admin/member') ? 'active' : '' }}">
                         <a href="{{ route('member.index') }}" class="menu-link">
                           <div>List</div>
                         </a>
                   </li>
             </ul>
        </li> --}}

        {{-- <li class="menu-item {{ request()->is('admin/activity-log*') ? 'active open' : '' }}">
            <a href="javascript:void(0)" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-report"></i>
                <div>Activity Logs</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item  {{ request()->is('admin/activity-log/email*') ? 'active' : '' }}">
                    <a href="{{ route('activity.log','email') }}" class="menu-link">
                        <div>Email Log</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->is('admin/activity-log/sms*') ? 'active' : '' }}">
                    <a href="{{ route('activity.log','sms') }}" class="menu-link">
                        <div>SMS Log</div>
                    </a>
                </li>
            </ul>
        </li> --}}

        {{-- @if(auth()->user()->role == App\Models\User::ROLE_SUPERADMIN)
            <li class="menu-item {{ request()->is('vote-count') ? 'active' : '' }}">
                <a href="{{ route('votes') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-trophy"></i>
                    <div data-i18n="Page 1">Votes</div>
                </a>
            </li>


        @endif --}}

        {{-- <li class="menu-item {{ request()->is('admin/recycle-bin*') ? 'active' : '' }}">
            <a href="{{ route('recycleBin') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-trash"></i>
                <div data-i18n="Page 1">Recently Deleted</div>
            </a>
        </li> --}}

    </ul>
</aside>
