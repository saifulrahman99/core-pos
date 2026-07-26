import { Link } from '@inertiajs/react';
import type { PropsWithChildren } from 'react';
import Heading from '@/components/heading';
import { Button } from '@/components/ui/button';
import { useCurrentUrl } from '@/hooks/use-current-url';
import { cn, toUrl } from '@/lib/utils';
import { edit as editAppearance } from '@/routes/appearance';
import { edit } from '@/routes/profile';
import { edit as editSecurity } from '@/routes/security';
import { edit as editStore } from '@/routes/store';
import type { NavItem } from '@/types';

const sidebarNavItems: NavItem[] = [
    {
        title: 'Profile',
        href: edit(),
        icon: null,
    },
    {
        title: 'Security',
        href: editSecurity(),
        icon: null,
    },
    {
        title: 'Appearance',
        href: editAppearance(),
        icon: null,
    },
    {
        title: 'Store',
        href: editStore(),
        icon: null,
    },
];

export default function SettingsLayout({ children }: PropsWithChildren) {
    const { isCurrentOrParentUrl } = useCurrentUrl();

    return (
        <div className="flex flex-1 flex-col overflow-hidden px-4 py-6">
            <div className="mb-6 shrink-0">
                <Heading
                    title="Settings"
                    description="Manage your profile and account settings"
                />
            </div>

            <div className="flex min-h-0 flex-1 flex-col gap-6 lg:flex-row lg:gap-12">
                <aside className="shrink-0 lg:w-48">
                    <nav
                        className="flex flex-row flex-wrap gap-1 lg:flex-col lg:space-y-1 lg:space-x-0"
                        aria-label="Settings"
                    >
                        {sidebarNavItems.map((item, index) => (
                            <Button
                                key={`${toUrl(item.href)}-${index}`}
                                size="sm"
                                variant="ghost"
                                asChild
                                className={cn('justify-start', {
                                    'bg-muted': isCurrentOrParentUrl(item.href),
                                })}
                            >
                                <Link href={item.href}>
                                    {item.icon && (
                                        <item.icon className="h-4 w-4" />
                                    )}
                                    {item.title}
                                </Link>
                            </Button>
                        ))}
                    </nav>
                </aside>

                <div className="min-h-0 flex-1 overflow-y-auto">
                    <section className="max-w-xl space-y-12 pb-12">
                        {children}
                    </section>
                </div>
            </div>
        </div>
    );
}
