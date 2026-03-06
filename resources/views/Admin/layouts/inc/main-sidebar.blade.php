 ========== Left Sidebar Start ==========
<div class="vertical-menu no_sidebar">

    <div data-simplebar class="h-100">

        <div id="sidebar-menu">

            <ul class="metismenu list-unstyled">
                <li>
                    <form class="app-search d-none d-lg-block">
                        <meta name="csrf-token" content="{{ csrf_token() }}">
                        <div class="position-relative">
                            <input type="text" id="myInput" onkeyup="myFunction()" class="form-control"
                                placeholder="ابحث هنا ..." onchange="SearchP($(this))">
                            <span class="fa fa-search"></span>
                        </div>
                    </form>
                </li>
            </ul>
            <ul class="metismenu list-unstyled " id="side-menu">


                <li>
                    <a href="{{ route('admin.index') }}" class="waves-effect">
                        <i class="mdi mdi-view-dashboard"></i>
                        <span>الرئيسية</span>
                    </a>
                </li>
                @canAdminAny('عرض المستخدمين')
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="fa fa-user-secret"></i>
                        <span> المستخدمين </span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('employees.index') }}"><i class="mdi mdi-album"></i>
                                <span> الموظفين</span></a></li>
                        <li><a href="{{ route('admins.index') }}"><i class="mdi mdi-album"></i>
                                <span> المستخدمين</span></a></li>
                        <li><a href="{{ route('roles.index') }}"><i class="mdi mdi-album"></i> <span>الادوار
                                </span></a>
                        </li>


                    </ul>
                </li>
                @endcanAdminAny


                @canAdminAny('عرض الاعدادات')
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="fa fa-cog"></i>
                        <span> الاعدادات </span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('settings.index') }}"><i class="mdi mdi-album"></i>
                                <span> اعدادات البرنامج</span></a></li>
                        <li><a href="{{ route('countries.index') }}"><i class="mdi mdi-album"></i> <span>المحافظات
                                </span></a>
                        </li>
                        <li><a href="{{ route('provinces.index') }}"><i class="mdi mdi-album"></i> <span>المدن
                                </span></a>
                        </li>
                        <li><a href="{{ route('regions.index') }}"><i class="mdi mdi-album"></i> <span>المناطق
                                </span></a>
                        </li>
                        <li><a href="{{ route('categories.index') }}"><i class="mdi mdi-album"></i> <span>التصنيفات
                                </span></a>
                        </li>
                        <li><a href="{{ route('unites.index') }}"><i class="mdi mdi-album"></i> <span>الوحدات
                                </span></a>
                        </li>
                        <li><a href="{{ route('companies.index') }}"><i class="mdi mdi-album"></i> <span>الشركات
                                </span></a>
                        </li>
                        <li><a href="{{ route('shapes.index') }}"><i class="mdi mdi-album"></i> <span>الاشكال
                                </span></a>
                        </li>
                    </ul>
                </li>
                @endcanAdminAny



                 @canAdminAny('عرض الفروع','عرض المخازن','عرض الاصناف')
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="fa fa-code-branch"></i>
                        <span> الفروع والمخازن </span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        @canAdminAny('عرض الفروع')

                        <li><a href="{{ route('branches.index') }}"><i class="mdi mdi-album"></i>
                                <span> الفروع</span></a></li>
                        @endcanAdminAny
                        @canAdminAny('عرض المخازن')

                        <li><a href="{{ route('storages.index') }}"><i class="mdi mdi-album"></i> <span>المخازن
                                </span></a>
                        </li>
                        @endcanAdminAny

                    </ul>
                </li>
                @endcanAdminAny


                  @canAdminAny('عرض المسئولون عن المخازن', 'تحضير الاصناف')
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="fa fa-list"></i>
                        <span> الاصناف </span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        @canAdminAny('عرض الاصناف')

                        <li><a href="{{ route('product.index') }}"><i class="mdi mdi-album"></i>
                                <span> الاصناف</span></a></li>
                        <!--<li><a href="{{ route('rasied_ayni.index') }}"><i class="mdi mdi-album"></i> <span> رصيد اول-->
                        <!--            مدة </span></a>-->
                        <!--</li>-->
                        <!--<li><a href="{{ route('productive-movement.index') }}"><i class="mdi mdi-album"></i>-->
                        <!--        <span>حركة الاصناف</span></a>-->
                        <!--</li>-->
                        <!--<li><a href="{{ route('admin.products-low-balance') }}"><i class="mdi mdi-album"></i>-->
                        <!--        <span>الاصناف النواقص</span></a>-->
                        <!--</li>-->
                        <!--<li><a href="{{ route('storage-check.index') }}"><i class="mdi mdi-album"></i>-->
                        <!--        <span>المخزون</span></a>-->
                        <!--</li>-->
                        @endcanAdminAny

                        @canAdminAny('تحضير الاصناف')

                        <!--<li><a href="{{ route('prepare-items.index') }}"><i class="mdi mdi-album"></i> <span>تحضير-->
                        <!--            الاصناف </span></a>-->
                        <!--</li>-->
                        @endcanAdminAny
                    </ul>
                </li>
                @endcanAdminAny




                @canAdminAny('عرض العملاء')
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="fa fa-user"></i>
                        <span> العملاء </span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('clients.index') }}"><i class="mdi mdi-album"></i>
                                <span> العملاء</span></a></li>
                       
                               <a href="{{ route('admin.clientsbystatus', ['status' => 'pending']) }}">
                                  <i class="mdi mdi-album"></i> 
                                 <span>طلبات التسجيل الوارده</span>
                                      </a>

                                <li><a href="{{ route('admin.clientsbystatus', ['status' => 'approved']) }}"><i class="mdi mdi-album"></i> <span>العملاء المقبوله   
                              </span></a></li>


                                <li><a href="{{ route('admin.clientsbystatus', ['status' => 'refused']) }}"><i class="mdi mdi-album"></i> <span>العملاء المرفوضه   
                              </span></a></li>
                        <!--</li>-->
                        <!--<li><a href="{{ route('admin.customerAccountStatements') }}"><i class="mdi mdi-album"></i>-->
                        <!--        <span>كشف حساب عميل </span></a>-->
                        <!--</li>-->
                        <!--<li><a href="{{ route('admin.customerAccountState') }}"><i class="mdi mdi-album"></i>-->
                        <!--        <span> كشف حساب عميل لفترة </span></a>-->
                        <!--</li>-->
                        @canAdminAny('عرض إعدادات تسديد العملاء')
                        <!--<li><a href="{{ route('client-payment-settings.index') }}"><i class="mdi mdi-album"></i>-->
                        <!--        <span>إعدادات تسديد العملاء </span></a>-->
                        <!--</li>-->
                        @endcanAdminAny
                        <!--<li><a href="{{ route('client-subscriptions.index') }}"><i class="mdi mdi-album"></i>-->
                        <!--        <span>اشتراكات العملاء</span></a>-->
                        <!--</li>-->

                    </ul>
                </li>
                @endcanAdminAny

                  @canAdminAny('كشف حساب عميل')
               <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                      <i class="fa fa-user-check"></i>
                      <span> حسابات العملاء </span>
                    </a>
                   <ul class="sub-menu" aria-expanded="false">
                     @canAdminAny('كشف حساب عميل')
                      <li><a href="{{ route('admin.customerAccountState') }}"><i class="mdi mdi-album"></i>
                             <span> كشف حساب عميل</span></a></li>
                             @endcanAdminAny

                   @canAdminAny('رصيد العملاء')
                       <li><a href="{{ route('admin.customers_balances') }}"><i class="mdi mdi-album"></i>
                            <span> رصيد العملاء  </span></a>
                            @endcanAdminAny
                   </li> 

                 </ul>
                </li> 
                @endcanAdminAny


             <!------------------------------------------------------------------------------------------------>
               <!------------------------------------------------------------------------------------------------>
                 <!------------------------------------------------------------------------------------------------>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="fa fa-code-branch"></i>
                        <span>  الكوبونات </span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                         @canAdminAny('اضافه كوبون') 
                    <li><a href="{{ route('coupons-converts.index') }}"><i class="mdi mdi-album"></i> <span>اضافه كوبون لعميل 
                              </span></a></li>

                         @endcanAdminAny 
                         @canAdminAny('عرض الكوبونات')
                       <li><a href="{{ route('admin.coupons-status', ['status' => 'pending']) }}"><i class="mdi mdi-album"></i> <span>طلبات التحويل الوارده    
                              </span></a></li>
                               <li><a href="{{ route('admin.coupons-status', ['status' => 'refused']) }}"><i class="mdi mdi-album"></i> <span>طلبات التحويل المرفوضه    
                              </span></a></li>
                              <li><a href="{{ route('admin.coupons-status', ['status' => 'approved']) }}"><i class="mdi mdi-album"></i> <span>طلبات التحويل المقبوله    
                              </span></a></li>
                               @endcanAdminAny

                    </ul>
                </li>
 <!------------------------------------------------------------------------------------------------>
               <!------------------------------------------------------------------------------------------------>
                 <!------------------------------------------------------------------------------------------------>



                @canAdminAny('عرض الموردين')
                <!--<li>-->
                <!--    <a href="javascript: void(0);" class="has-arrow waves-effect">-->
                <!--        <i class="fa fa-user-check"></i>-->
                <!--        <span> الموردين </span>-->
                <!--    </a>-->
                <!--    <ul class="sub-menu" aria-expanded="false">-->
                <!--        <li><a href="{{ route('suppliers.index') }}"><i class="mdi mdi-album"></i>-->
                <!--                <span> الموردين</span></a></li>-->
                <!--        <li><a href="{{ route('supplier_vouchers.index') }}"><i class="mdi mdi-album"></i> <span>-->
                <!--                    ايصلات المورد </span></a>-->
                <!--        </li>-->
                <!--        <li><a href="{{ route('admin.supplierAccountStatements') }}"><i class="mdi mdi-album"></i>-->
                <!--                <span>كشف حساب مورد </span></a>-->
                <!--        </li>-->

                <!--    </ul>-->
                <!--</li>-->
                @endcanAdminAny
                @canAdminAny('عرض المناديب')
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="fa fa-users-cog"></i>
                        <span> المناديب</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        @canAdminAny('عرض المناديب')

                        <li><a href="{{ route('representatives.index') }}"><i class="mdi mdi-album"></i>
                                <span> المناديب</span></a></li>
                        @endcanAdminAny
                    </ul>
                </li>
                @endcanAdminAny

                @canAdminAny('عرض المسئولون عن المخازن')

                <!--<li>-->
                <!--    <a href="javascript: void(0);" class="has-arrow waves-effect">-->
                <!--        <i class="fa fa-users-cog"></i>-->
                <!--        <span> المسئولون عن المخازن </span>-->
                <!--    </a>-->
                <!--    <ul class="sub-menu" aria-expanded="false">-->
                <!--        <li><a href="{{ route('store-managers.index') }}"><i class="fa fa-route"></i> <span>متابعة-->
                <!--                    تحضير الصناف </span></a>-->
                <!--        </li>-->

                <!--    </ul>-->
                <!--</li>-->
                @endcanAdminAny

               

              

                @canAdminAny('عرض المشتريات')

                <!--<li>-->
                <!--    <a href="javascript: void(0);" class="has-arrow waves-effect">-->
                <!--        <i class="fa fa-shopping-cart"></i>-->
                <!--        <span> المشتريات </span>-->
                <!--    </a>-->
                <!--    <ul class="sub-menu" aria-expanded="false">-->
                <!--        <li><a href="{{ route('purchases-requests.index') }}"><i class="mdi mdi-album"></i> <span>-->
                <!--                    طلبات الشراء </span></a>-->
                <!--        </li>-->
                <!--        <li><a href="{{ route('purchases.index') }}"><i class="mdi mdi-album"></i>-->
                <!--                <span> المشتريات</span></a></li>-->
                <!--        <li><a href="{{ route('purchasesBills.index') }}"><i class="mdi mdi-album"></i> <span> تقرير-->
                <!--                    المشتريات </span></a>-->
                <!--        </li>-->
                <!--    </ul>-->
                <!--</li>-->
                @endcanAdminAny



                {{-- @canAdminAny('عرض الاصناف')
                <!--<li>-->
                <!--    <a href="javascript: void(0);" class="has-arrow waves-effect">-->
                <!--        <i class="fa fa-download"></i>-->
                <!--        <span> التصنيع </span>-->
                <!--    </a>-->
                <!--    <ul class="sub-menu" aria-expanded="false">-->
                <!--        <li><a href="{{ route('itemInstallations.index') }}"><i class="mdi mdi-album"></i>-->
                <!--                <span> تكوين الاصناف</span></a></li>-->
                <!--        <li><a href="{{ route('productions.index') }}"><i class="mdi mdi-album"></i> <span> الانتاج-->
                <!--                </span></a>-->
                <!--        </li>-->


                <!--    </ul>-->
                <!--</li>-->
                @endcanAdminAny --}}



                @canAdminAny('عرض الفواتير')
                <!--<li>-->
                <!--    <a href="javascript: void(0);" class="has-arrow waves-effect">-->
                <!--        <i class="fa fa-money-bill"></i>-->
                <!--        <span> المبيعات </span>-->
                <!--    </a>-->
                <!--    <ul class="sub-menu" aria-expanded="false">-->
                <!--        <li><a href="{{ route('sales.index') }}"><i class="mdi mdi-album"></i>-->
                <!--                <span> المبيعات</span></a></li>-->
                <!--        <li><a href="{{ route('salesBills.index') }}"><i class="mdi mdi-album"></i> <span> تقارير-->
                <!--                    المبيعات </span></a>-->
                <!--        </li>-->


                <!--    </ul>-->
                <!--</li>-->
                @endcanAdminAny



                @canAdminAny('عرض المرتجعات')

                <!--<li>-->
                <!--    <a href="javascript: void(0);" class="has-arrow waves-effect">-->
                <!--        <i class="fa fa-money-bill"></i>-->
                <!--        <span> المرتجعات </span>-->
                <!--    </a>-->
                <!--    <ul class="sub-menu" aria-expanded="false">-->
                <!--        <li><a href="{{ route('head_back_sales.index') }}"><i class="mdi mdi-album"></i>-->
                <!--                <span> مرتجع المبيعات</span></a></li>-->
                <!--        <li><a href="{{ route('head_back_purchases.index') }}"><i class="mdi mdi-album"></i> <span>-->
                <!--                    مرتجع المشتريات </span></a>-->
                <!--        </li>-->



                <!--    </ul>-->
                <!--</li>-->
                @endcanAdminAny


                @canAdminAny('عرض الاهلاك')
                <!--<li class="nav-item">-->
                <!--    <a class="nav-link menu-link" href="{{ route('destruction.index') }}">-->
                <!--        <i class="fa fa-industry"></i>-->
                <!--        <span> الاهلاك </span>-->
                <!--    </a>-->
                <!--</li>-->
                @endcanAdminAny

                @canAdminAny('عرض التسوية')
                <!--<li>-->
                <!--    <a href="javascript: void(0);" class="has-arrow waves-effect">-->
                <!--        <i class="mdi mdi-buffer"></i>-->
                <!--        <span> التسوية </span>-->
                <!--    </a>-->
                <!--    <ul class="sub-menu" aria-expanded="false">-->
                <!--        <li><a href="{{ route('product-adjustments.index') }}"><i class="mdi mdi-album"></i>-->
                <!--                <span>تسوية المنتجات</span></a></li>-->
                <!--        <li><a href="{{ route('client-adjustments.index') }}"><i class="mdi mdi-album"></i>-->
                <!--                <span>تسوية العملاء</span></a></li>-->
                <!--    </ul>-->
                <!--</li>-->
                @endcanAdminAny
               

            </ul>
        </div>
    </div>
</div>

<script>
    function myFunction() {
        var input, filter, ul, li, a, i, txtValue;
        input = document.getElementById("myInput");
        filter = input.value.toUpperCase();
        ul = document.getElementById("side-menu");
        li = ul.getElementsByTagName("li");
        for (i = 0; i < li.length; i++) {
            a = li[i];
            txtValue = a.textContent || a.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                li[i].style.display = "";
            } else {
                li[i].style.display = "none";
            }
        }
    }
</script> 
