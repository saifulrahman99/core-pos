import { usePage } from '@inertiajs/react';
import AppLogoIcon from '@/components/app-logo-icon';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { useInitials } from '@/hooks/use-initials';

export default function AppLogo() {
    const { store } = usePage().props;
    const getInitials = useInitials();

    return (
        <>
            <Avatar className="size-8 overflow-hidden rounded-lg">
                <AvatarImage src={store?.logo_url} alt={store?.name ?? 'Store'} />
                <AvatarFallback className="rounded-lg bg-sidebar-primary text-sidebar-primary-foreground">
                    <AppLogoIcon className="size-5 fill-current text-white dark:text-black" />
                </AvatarFallback>
            </Avatar>
            <div className="ml-1 grid flex-1 text-left text-sm">
                <span className="mb-0.5 truncate leading-tight font-semibold">
                    {store?.name ?? 'POS'}
                </span>
            </div>
        </>
    );
}
