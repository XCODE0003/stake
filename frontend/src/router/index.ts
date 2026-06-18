import {
    createRouter,
    createWebHistory,
    type RouteLocationRaw,
} from 'vue-router';
import { useAuthStore } from '@/stores/auth';
import type { User } from '@/types';

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: '/',
            name: 'welcome',
            component: () => import('@/views/WelcomeView.vue'),
        },
        {
            path: '/login',
            name: 'login',
            component: () => import('@/views/auth/LoginView.vue'),
            meta: { guestOnly: true },
        },
        {
            path: '/register',
            name: 'register',
            component: () => import('@/views/auth/RegisterView.vue'),
            meta: { guestOnly: true },
        },
        {
            path: '/verify-email',
            name: 'verify-email',
            component: () => import('@/views/auth/VerifyEmailView.vue'),
            meta: { requiresAuth: true },
        },
        {
            path: '/pending',
            name: 'pending',
            component: () => import('@/views/PendingApprovalView.vue'),
            meta: { requiresAuth: true },
        },
        {
            path: '/dashboard',
            name: 'dashboard',
            component: () => import('@/views/DashboardView.vue'),
            meta: { requiresAuth: true },
        },
        {
            path: '/support',
            name: 'support',
            component: () => import('@/views/SupportView.vue'),
            meta: { requiresAuth: true },
        },
        {
            path: '/transactions',
            name: 'transactions',
            component: () => import('@/views/TransactionsView.vue'),
            meta: { requiresAuth: true },
        },
        {
            path: '/statistics',
            name: 'statistics',
            component: () => import('@/views/StatisticsView.vue'),
            meta: { requiresAuth: true },
        },
        { path: '/:pathMatch(.*)*', redirect: '/' },
    ],
});

/** Where a logged-in user belongs based on their onboarding stage. */
function destinationFor(user: User): RouteLocationRaw {
    if (!user.email_verified) {
        return { name: 'verify-email' };
    }
    if (user.needs_approval) {
        return { name: 'pending' };
    }
    return { name: 'dashboard' };
}

router.beforeEach(async (to) => {
    const auth = useAuthStore();

    if (!auth.ready) {
        await auth.fetchMe();
    }

    const loggedIn = auth.isAuthenticated && auth.user !== null;

    if (to.meta.guestOnly && loggedIn) {
        return destinationFor(auth.user as User);
    }

    if (to.meta.requiresAuth && !loggedIn) {
        return { name: 'login' };
    }

    // Funnel logged-in users to the right stage of the flow.
    if (loggedIn && to.meta.requiresAuth) {
        const user = auth.user as User;

        if (!user.email_verified && to.name !== 'verify-email') {
            return { name: 'verify-email' };
        }
        if (user.email_verified && user.needs_approval && to.name !== 'pending') {
            return { name: 'pending' };
        }
        if (user.approved && (to.name === 'verify-email' || to.name === 'pending')) {
            return { name: 'dashboard' };
        }
    }

    return true;
});

export default router;
