import { Link, router } from '@inertiajs/react';
import { useState } from 'react';
import { LogOut } from 'lucide-react';
import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
} from '@/components/ui/alert-dialog';
import {
    DropdownMenuItem,
} from '@/components/ui/dropdown-menu';
import { UserInfo } from '@/components/user-info';
import { useMobileNavigation } from '@/hooks/use-mobile-navigation';
import { logout } from '@/routes';
import type { User } from '@/types';

type Props = {
    user: User;
};

export function UserMenuContent({ user }: Props) {
    const cleanup = useMobileNavigation();
    const [showLogoutConfirm, setShowLogoutConfirm] = useState(false);

    const handleLogout = () => {
        cleanup();
        router.flushAll();
    };

    return (
        <>
            <div className="px-1 py-1.5 text-left text-sm">
                <UserInfo user={user} showEmail={true} />
            </div>

            <DropdownMenuItem
                className="cursor-pointer"
                onSelect={(e) => {
                    e.preventDefault();
                    setShowLogoutConfirm(true);
                }}
                data-test="logout-button"
            >
                <LogOut className="mr-2" />
                Log out
            </DropdownMenuItem>

            <AlertDialog open={showLogoutConfirm} onOpenChange={setShowLogoutConfirm}>
                <AlertDialogContent>
                    <AlertDialogHeader>
                        <AlertDialogTitle>Are you sure you want to log out?</AlertDialogTitle>
                        <AlertDialogDescription>
                            You will be logged out of your account and redirected to the login page.
                        </AlertDialogDescription>
                    </AlertDialogHeader>
                    <AlertDialogFooter>
                        <AlertDialogCancel>Cancel</AlertDialogCancel>
                        <AlertDialogAction asChild>
                            <Link
                                href={logout()}
                                as="button"
                                onClick={handleLogout}
                            >
                                Log out
                            </Link>
                        </AlertDialogAction>
                    </AlertDialogFooter>
                </AlertDialogContent>
            </AlertDialog>
        </>
    );
}
