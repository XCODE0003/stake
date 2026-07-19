/**
 * UI copy per locale.
 *
 * `en` is the source of truth for the set of keys; `ru` must provide every key
 * (enforced by the `Record<MessageKey, string>` type). The active locale is
 * derived from the brand/theme — see `localeForTheme` in `./index.ts` — so the
 * Stake build stays English while every other brand renders Russian.
 */
export const en = {
    // Welcome
    'welcome.tagline': 'Working with us is now easier, faster and more comfortable!',
    'welcome.dashboard': 'Dashboard',
    'welcome.login': 'Login',
    'welcome.register': 'Register',
    'welcome.affiliateProgram': 'Affiliate program.',

    // Login
    'login.title': 'Sign In',
    'login.description': 'Enter your email and password to sign in',
    'login.email': 'Email',
    'login.password': 'Password',
    'login.passwordPlaceholder': 'Password',
    'login.submit': 'Sign In',
    'login.noAccount': "Don't have an account?",
    'login.registerLink': 'Register',

    // Register
    'register.title': 'Create an Account',
    'register.description': 'Register to access the affiliate dashboard',
    'register.email': 'Email',
    'register.username': 'Username',
    'register.usernamePlaceholder': 'Username',
    'register.password': 'Password',
    'register.passwordPlaceholder': 'Password',
    'register.terms': 'I am 18 or older and agree to the',
    'register.termsLink': 'Terms and Conditions',
    'register.submit': 'Register',
    'register.haveAccount': 'Already have an account?',
    'register.loginLink': 'Log in',

    // Verify email
    'verify.title': 'Verify your email',
    'verify.description': 'Enter the 6-digit code we sent to your email',
    'verify.sentTo': 'We sent a code to',
    'verify.resent': 'A new code has been sent to your email.',
    'verify.submit': 'Verify',
    'verify.resend': 'Resend code',
    'verify.logout': 'Log out',

    // Pending approval
    'pending.title': 'Please wait for your account to be approved',
    'pending.body':
        "Your email is verified. A manager will review your application and activate the account. Once approved, you'll see your terms and be able to link a payout wallet.",
    'pending.check': 'Check status',
    'pending.logout': 'Log out',

    // Dashboard
    'dashboard.welcomeBack': 'Welcome back',
    'dashboard.subtitle': 'Here are your current affiliate terms.',
    'dashboard.fixedPayment': 'Fixed payment',
    'dashboard.streams': 'Streams',
    'dashboard.referrals': 'Referrals',
    'dashboard.payoutWallet': 'Payout wallet',
    'dashboard.payoutWalletDesc': 'Link a wallet address to receive payouts.',
    'dashboard.noWallet': 'No wallet linked yet.',
    'dashboard.editWallet': 'Edit wallet',
    'dashboard.linkWallet': 'Link payout wallet',

    // Statistics
    'statistics.title': 'Statistics',
    'statistics.subtitle': 'Your affiliate performance.',
    'statistics.referrals': 'Referrals',
    'statistics.casinoProfit': 'Casino profit',
    'statistics.yourProfit': 'Your profit',

    // Support
    'support.title': 'Online support',
    'support.subtitle': 'Open a ticket and our team will get back to you.',
    'support.newTicket': 'New ticket',
    'support.placeholder': 'Describe your question or issue…',
    'support.send': 'Send',
    'support.loading': 'Loading…',
    'support.empty': 'You have no tickets yet.',
    'support.statusOpen': 'Open',
    'support.statusAnswered': 'Answered',
    'support.statusClosed': 'Closed',
    'support.reply': 'Support reply',

    // Transactions
    'transactions.title': 'Transactions',
    'transactions.subtitle': 'Your payout history.',
    'transactions.emptyTitle': 'No transactions yet',
    'transactions.emptyBody': "You don't have any transactions yet.",

    // Sidebar / navigation
    'nav.affiliate': 'Affiliate',
    'nav.dashboard': 'Dashboard',
    'nav.support': 'Online support',
    'nav.transactions': 'Transactions',
    'nav.statistics': 'Statistics',
    'nav.logout': 'Log out',
    'nav.openMenu': 'Open menu',
    'nav.closeMenu': 'Close menu',

    // Password input
    'password.show': 'Show password',
    'password.hide': 'Hide password',
} as const;

export type MessageKey = keyof typeof en;

export const ru: Record<MessageKey, string> = {
    // Welcome
    'welcome.tagline': 'Работать с нами теперь проще, быстрее и удобнее!',
    'welcome.dashboard': 'Личный кабинет',
    'welcome.login': 'Войти',
    'welcome.register': 'Регистрация',
    'welcome.affiliateProgram': 'Партнёрская программа.',

    // Login
    'login.title': 'Вход',
    'login.description': 'Введите email и пароль, чтобы войти',
    'login.email': 'Email',
    'login.password': 'Пароль',
    'login.passwordPlaceholder': 'Пароль',
    'login.submit': 'Войти',
    'login.noAccount': 'Нет аккаунта?',
    'login.registerLink': 'Регистрация',

    // Register
    'register.title': 'Создать аккаунт',
    'register.description': 'Зарегистрируйтесь, чтобы получить доступ к партнёрскому кабинету',
    'register.email': 'Email',
    'register.username': 'Имя пользователя',
    'register.usernamePlaceholder': 'Имя пользователя',
    'register.password': 'Пароль',
    'register.passwordPlaceholder': 'Пароль',
    'register.terms': 'Мне есть 18 лет, и я согласен с',
    'register.termsLink': 'Условиями использования',
    'register.submit': 'Зарегистрироваться',
    'register.haveAccount': 'Уже есть аккаунт?',
    'register.loginLink': 'Войти',

    // Verify email
    'verify.title': 'Подтвердите email',
    'verify.description': 'Введите 6-значный код, отправленный на ваш email',
    'verify.sentTo': 'Мы отправили код на',
    'verify.resent': 'Новый код отправлен на ваш email.',
    'verify.submit': 'Подтвердить',
    'verify.resend': 'Отправить код повторно',
    'verify.logout': 'Выйти',

    // Pending approval
    'pending.title': 'Ожидайте подтверждения вашего аккаунта',
    'pending.body':
        'Ваш email подтверждён. Менеджер рассмотрит вашу заявку и активирует аккаунт. После подтверждения вы увидите свои условия и сможете привязать кошелёк для выплат.',
    'pending.check': 'Проверить статус',
    'pending.logout': 'Выйти',

    // Dashboard
    'dashboard.welcomeBack': 'С возвращением',
    'dashboard.subtitle': 'Ваши текущие партнёрские условия.',
    'dashboard.fixedPayment': 'Фиксированная выплата',
    'dashboard.streams': 'Потоки',
    'dashboard.referrals': 'Рефералы',
    'dashboard.payoutWallet': 'Кошелёк для выплат',
    'dashboard.payoutWalletDesc': 'Привяжите адрес кошелька для получения выплат.',
    'dashboard.noWallet': 'Кошелёк ещё не привязан.',
    'dashboard.editWallet': 'Изменить кошелёк',
    'dashboard.linkWallet': 'Привязать кошелёк',

    // Statistics
    'statistics.title': 'Статистика',
    'statistics.subtitle': 'Ваши партнёрские показатели.',
    'statistics.referrals': 'Рефералы',
    'statistics.casinoProfit': 'Прибыль казино',
    'statistics.yourProfit': 'Ваша прибыль',

    // Support
    'support.title': 'Онлайн-поддержка',
    'support.subtitle': 'Создайте обращение, и наша команда ответит вам.',
    'support.newTicket': 'Новое обращение',
    'support.placeholder': 'Опишите ваш вопрос или проблему…',
    'support.send': 'Отправить',
    'support.loading': 'Загрузка…',
    'support.empty': 'У вас пока нет обращений.',
    'support.statusOpen': 'Открыто',
    'support.statusAnswered': 'Отвечено',
    'support.statusClosed': 'Закрыто',
    'support.reply': 'Ответ поддержки',

    // Transactions
    'transactions.title': 'Транзакции',
    'transactions.subtitle': 'История ваших выплат.',
    'transactions.emptyTitle': 'Транзакций пока нет',
    'transactions.emptyBody': 'У вас пока нет транзакций.',

    // Sidebar / navigation
    'nav.affiliate': 'Партнёр',
    'nav.dashboard': 'Главная',
    'nav.support': 'Поддержка',
    'nav.transactions': 'Транзакции',
    'nav.statistics': 'Статистика',
    'nav.logout': 'Выйти',
    'nav.openMenu': 'Открыть меню',
    'nav.closeMenu': 'Закрыть меню',

    // Password input
    'password.show': 'Показать пароль',
    'password.hide': 'Скрыть пароль',
};

export const messages = { en, ru } as const;

export type Locale = keyof typeof messages;
