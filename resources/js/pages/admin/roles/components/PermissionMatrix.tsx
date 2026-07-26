import { Checkbox } from '@/components/ui/checkbox';
import { Separator } from '@/components/ui/separator';
import InputError from '@/components/input-error';

type PermissionItem = {
    id: number;
    name: string;
    guard_name: string;
};

type PermissionMatrixProps = {
    permissions: Record<string, PermissionItem[]>;
    selected: string[];
    onChange: (permissions: string[]) => void;
    errors?: string;
};

export default function PermissionMatrix({ permissions, selected, onChange, errors }: PermissionMatrixProps) {
    function togglePermission(name: string) {
        if (selected.includes(name)) {
            onChange(selected.filter((p) => p !== name));
        } else {
            onChange([...selected, name]);
        }
    }

    function toggleGroup(items: PermissionItem[]) {
        const groupNames = items.map((p) => p.name);
        const allSelected = groupNames.every((name) => selected.includes(name));

        if (allSelected) {
            onChange(selected.filter((name) => !groupNames.includes(name)));
        } else {
            const newPermissions = [...selected];
            for (const name of groupNames) {
                if (!newPermissions.includes(name)) {
                    newPermissions.push(name);
                }
            }
            onChange(newPermissions);
        }
    }

    const groups = Object.entries(permissions);

    return (
        <div className="space-y-4">
            <div>
                <p className="text-sm font-medium">Permissions</p>
                <p className="text-xs text-muted-foreground">
                    Select the permissions for this role
                </p>
            </div>

            <div className="space-y-4">
                {groups.map(([group, items], groupIndex) => {
                    const groupNames = items.map((p) => p.name);
                    const allSelected = groupNames.every((name) => selected.includes(name));
                    const someSelected = groupNames.some((name) => selected.includes(name));

                    return (
                        <div key={group}>
                            {groupIndex > 0 && <Separator className="mb-4" />}

                            <div className="space-y-3">
                                <div className="flex items-center gap-2">
                                    <Checkbox
                                        id={`group-${group}`}
                                        checked={allSelected}
                                        ref={(el) => {
                                            if (el) {
                                                const input = el.querySelector('input[type="checkbox"]') as HTMLInputElement | null;
                                                if (input) {
                                                    input.indeterminate = someSelected && !allSelected;
                                                }
                                            }
                                        }}
                                        onCheckedChange={() => toggleGroup(items)}
                                    />
                                    <label
                                        htmlFor={`group-${group}`}
                                        className="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                                    >
                                        {group}
                                    </label>
                                </div>

                                <div className="ml-6 grid grid-cols-1 gap-2 sm:grid-cols-2 md:grid-cols-3">
                                    {items.map((permission) => (
                                        <div key={permission.id} className="flex items-center gap-2">
                                            <Checkbox
                                                id={`permission-${permission.id}`}
                                                checked={selected.includes(permission.name)}
                                                onCheckedChange={() => togglePermission(permission.name)}
                                            />
                                            <label
                                                htmlFor={`permission-${permission.id}`}
                                                className="text-sm leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                                            >
                                                {permission.name}
                                            </label>
                                        </div>
                                    ))}
                                </div>
                            </div>
                        </div>
                    );
                })}
            </div>

            <InputError message={errors} />
        </div>
    );
}
