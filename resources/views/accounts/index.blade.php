<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Myfin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <style>
        .disabled {
            display: none;
        }
    </style>
</head>


<body class="sb-nav">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="index.html">Myfin</a>
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i
                class="fas fa-bars"></i></button>
        <!-- Navbar Search-->
        <!--<form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
            <div class="input-group">
                <input class="form-control" type="text" placeholder="Пошук..." aria-label="Пошук..."
                    aria-describedby="btnNavbarSearch" />
                <button class="btn btn-primary" id="btnNavbarSearch" type="button"><i
                        class="fas fa-search"></i></button>
            </div>
        </form> hello-->
        <!-- Navbar-->
        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown"
                    aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="#!">Налаштування</a></li>
                    <li><a class="dropdown-item" href="#!">Журнал активності</a></li>
                    <li>
                        <hr class="dropdown-divider" />
                    </li>
                    <li> <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-responsive-nav-link :href="route('logout')"
                                                   onclick="event.preventDefault();
                                        this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-responsive-nav-link>
                        </form></li>
                </ul>
            </li>
        </ul>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">Центр</div>
                        <a class="nav-link" href="index.html">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Приладова панель
                        </a>
                        <a class="nav-link" href="{{route("transactions.index")}}">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Історія транзакцій
                        </a>
                        <div class="sb-sidenav-menu-heading">Інтерфейс</div>
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                            data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                            <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                            Макети
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne"
                            data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="layout-static.html">Статична навігація</a>
                                <a class="nav-link" href="layout-sidenav-light.html">Світла тема</a>
                            </nav>
                        </div>
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePages"
                            aria-expanded="false" aria-controls="collapsePages">
                            <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
                            Сторінки
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapsePages" aria-labelledby="headingTwo"
                            data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav accordion" id="sidenavAccordionPages">
                                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                                    data-bs-target="#pagesCollapseAuth" aria-expanded="false"
                                    aria-controls="pagesCollapseAuth">
                                    Аутентифікація
                                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                </a>
                                <div class="collapse" id="pagesCollapseAuth" aria-labelledby="headingOne"
                                    data-bs-parent="#sidenavAccordionPages">
                                    <nav class="sb-sidenav-menu-nested nav">
                                        <a class="nav-link" href="login.html">Вхід</a>
                                        <a class="nav-link" href="register.html">Реєстрація</a>
                                        <a class="nav-link" href="password.html">Забули пароль</a>
                                    </nav>
                                </div>
                                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                                    data-bs-target="#pagesCollapseError" aria-expanded="false"
                                    aria-controls="pagesCollapseError">
                                    Помилка
                                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                </a>
                                <div class="collapse" id="pagesCollapseError" aria-labelledby="headingOne"
                                    data-bs-parent="#sidenavAccordionPages">
                                    <nav class="sb-sidenav-menu-nested nav">
                                        <a class="nav-link" href="401.html">401 Page</a>
                                        <a class="nav-link" href="404.html">404 Page</a>
                                        <a class="nav-link" href="500.html">500 Page</a>
                                    </nav>
                                </div>
                            </nav>
                        </div>
                        <div class="sb-sidenav-menu-heading">Додатки</div>
                        <a class="nav-link" href="charts.html">
                            <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                            Діаграми
                        </a>
                        <a class="nav-link" href="tables.html">
                            <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                            Таблиці
                        </a>
                    </div>
                </div>
                <!-- <div class="sb-sidenav-footer">
                    <div class="small">Logged in as:</div>
                    Start Bootstrap
                </div> -->
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <div class="container-fluid px-4">
                <h1 class="mt-4">Список ваших рахунків</h1>
                <!-- <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item active">Dashboard123</li>
                    <li class="breadcrumb-item active">Dashboard123</li>
                    <li class="breadcrumb-item active">Dashboard123</li>
                </ol> -->
                <div>
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createModal">
                        Створити
                    </button>

                    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="createModalLabel">Створити рахунок</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрити"></button>
                                </div>
                                <div class="modal-body">
                                    <form method="post" action="{{ route('accounts.create') }}">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="InputName1" class="form-label">Name</label>
                                            <input type="text" class="form-control" id="InputName1" name="name">
                                        </div>
                                        <div class="mb-3">
                                            <label for="InputAmount1" class="form-label">Amount</label>
                                            <input type="number" step="0.01" class="form-control" id="InputAmount1" name="amount">
                                        </div>
                                        <div class="modal-footer p-0 pt-3">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрити</button>
                                            <button type="submit" class="btn btn-primary">Зберегти</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    @foreach($accounts as $account)
                        <div class="col-xl-3 col-md-6">
                            <div class="card">
                                <div class="position-absolute top-0 end-0">

                                    <form method="POST" action="{{ route('accounts.delete', [$account]) }}"
                                          onsubmit="return confirm('Ви впевнені, що хочете видалити цей обліковий запис?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Видалити</button>
                                    </form>

                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal{{ $account->id }}">
                                        Змінити
                                    </button>

                                    <!-- Modal -->
                                    <div class="modal fade" id="editModal{{ $account->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $account->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editModalLabel{{ $account->id }}">Редагувати рахунок</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрити"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form method="POST" action="{{ route('accounts.update', $account->id) }}">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="form-group mb-3">
                                                            <label for="AccountName{{ $account->id }}">Назва рахунку</label>
                                                            <input type="text" class="form-control" id="AccountName{{ $account->id }}"
                                                                   name="name" value="{{ $account->name }}">
                                                        </div>
                                                        <div class="form-group mb-3">
                                                            <label for="Amount{{ $account->id }}">Баланс</label>
                                                            <input type="number" class="form-control" id="Amount{{ $account->id }}" name="amount" value="{{ $account->amount }}">
                                                        </div>
                                                        <div class="modal-footer p-0 pt-3">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрити</button>
                                                            <button type="submit" class="btn btn-primary">Зберегти</button>
                                                        </div>
                                                    </form>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#NewTransactionModal{{ $account->id }}">
                                        додати транзакцію
                                    </button>

                                    <div class="modal fade" id="NewTransactionModal{{ $account->id }}" tabindex="-1" aria-labelledby="NewTransactionModalLabel{{ $account->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="NewTransactionModalLabel{{ $account->id }}">додати нову транзакцію</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрити"></button>
                                                </div>
                                                <div class="modal-body" id="transactionsContainer">
                                                    <form method="POST" class="js--transactionClassForm" id="transactionForm{{$account->id}}" action="{{ route('transactions.create') }}">
                                                        @csrf
                                                        @method('POST')
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="radio" id="radioDeposit{{ $account->id }}" value="deposit">
                                                            <label class="form-check-label" for="radioDeposit{{ $account->id }}">
                                                                Поповнення
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="radio" id="radioSpend{{ $account->id }}" value="spend">
                                                            <label class="form-check-label" for="radioSpend{{ $account->id }}">
                                                                Витрачення
                                                            </label>
                                                        </div>
                                                        <span class="disabled js--radioError" style="color: red;">Необхідно вибрати тип транзакції</span>

                                                        <div class="dropdown mt-2">
                                                            <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                                Вибрати категорію
                                                            </button>
                                                            <ul class="dropdown-menu js--categoryList"></ul>
                                                            <div class="js--categoryError disabled text-danger mt-1">Виберіть категорію</div>
                                                            <input type="hidden" name="category_id" class="js--categoryInput">
                                                        </div>
                                                        <span class="disabled js--dropListError" style="color: red;" >Будь ласка виберіть категорію</span>
                                                        <div class="form-group mb-3">
                                                            <label for="">Кількість</label>
                                                            <input type="number" class="form-control" id="" name="amount" min="0">
                                                            <input type="hidden" name="account_id" value="{{ $account->id }}">
                                                        </div>
                                                        <span class="disabled js--amountError" style="color: red;" >Будь ласка вкажіть суму</span>

                                                        <div class="form-group mb-3">
                                                            <label for="desc{{$account->id}}">Опис</label>
                                                            <input type="text" class="form-control" id="desc{{$account->id}}" name="description">
                                                        </div>

                                                        <div class="modal-footer p-0 pt-3">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрити</button>
                                                            <button type="submit" class="btn btn-primary">Зберегти</button>
                                                        </div>
                                                    </form>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    <form>

                                    </form>

                                </div>
                                <div class="card-body">
                                    <h4 class="card-title">{{ $account->name }}</h4>
                                    <p class="card-text">Баланс: {{ $account->amount }}</p>

                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>





                <!-- <footer class="py-4 bg-light mt-auto">
                        <div class="container-fluid px-4">
                            <div class="d-flex align-items-center justify-content-between small">
                                <div class="text-muted">Copyright &copy; Your Website 2023</div>
                                <div>
                                    <a href="#">Privacy Policy</a>
                                    &middot;
                                    <a href="#">Terms &amp; Conditions</a>
                                </div>
                            </div>
                        </div>
                    </footer> -->
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="{{ asset('assets/demo/chart-area-demo.js') }}"></script>
    <script src="{{asset('assets/demo/chart-area-demo.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
        crossorigin="anonymous"></script>
    <script>

        // --- Вибір категорії ---
        document.addEventListener('click', (e) => {
            if (e.target.matches('.dropdown-item')) {
                e.preventDefault();
                const categoryName = e.target.textContent;
                const categoryId = e.target.dataset.categoryId;

                const dropdown = e.target.closest('.dropdown');
                const btn = dropdown.querySelector('button');
                const input = dropdown.querySelector('.js--categoryInput');

                // Оновлюємо кнопку і приховане поле
                btn.textContent = categoryName;
                btn.dataset.categoryId = categoryId;
                input.value = categoryId; // 🟢 ось головне — щоб request('category_id') працював
            }
        });

        // --- Підвантаження категорій ---
        document.querySelectorAll('input[name="radio"]').forEach(radio => {
            radio.addEventListener('change', async (event) => {
                const type = event.target.value;
                const form = event.target.closest('form');
                const categoryList = form.querySelector('.js--categoryList');
                const dropdownBtn = form.querySelector('.dropdown-toggle');
                const categoryInput = form.querySelector('.js--categoryInput');

                // Скидаємо вибрану категорію
                dropdownBtn.textContent = "Вибрати категорію";
                dropdownBtn.removeAttribute('data-category-id');
                categoryInput.value = ''; // 🟢 очищаємо значення

                categoryList.innerHTML = '<li class="dropdown-item text-muted">Завантаження...</li>';

                try {
                    const response = await fetch(`/categories/${type}`);
                    const categories = await response.json();

                    categoryList.innerHTML = '';
                    categories.forEach(cat => {
                        const li = document.createElement('li');
                        li.innerHTML = `<a class="dropdown-item" href="#" data-category-id="${cat.id}">${cat.name}</a>`;
                        categoryList.appendChild(li);
                    });

                } catch (error) {
                    console.error(error);
                    categoryList.innerHTML = '<li class="dropdown-item text-danger">Помилка завантаження</li>';
                }
            });
        });

        // --- Валідація ---
        Array.from(document.getElementsByClassName("js--transactionClassForm")).map((form) => {
            form.addEventListener("submit", (Event) => {
                const formData = new FormData(form)
                const radio = formData.get("radio")
                const input = formData.get("amount")
                const categoryId = formData.get("category_id")

                let valid = true;

                // Radio
                if (radio == null) {
                    form.querySelector(".js--radioError").classList.remove("disabled")
                    valid = false;
                } else {
                    form.querySelector(".js--radioError").classList.add("disabled")
                }

                // Amount
                if (input === "") {
                    form.querySelector(".js--amountError").classList.remove("disabled")
                    valid = false;
                } else {
                    form.querySelector(".js--amountError").classList.add("disabled")
                }

                // Category
                if (!categoryId) {
                    form.querySelector(".js--categoryError").classList.remove("disabled")
                    valid = false;
                } else {
                    form.querySelector(".js--categoryError").classList.add("disabled")
                }

                if (!valid) {
                    Event.preventDefault();
                }
            })
        })


    </script>
        <script src="{{ asset('js/datatables-simple-demo.js') }}"></script>
</body>

</html>
