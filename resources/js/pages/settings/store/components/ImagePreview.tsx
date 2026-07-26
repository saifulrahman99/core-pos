export default function ImagePreview({ url, alt }: { url: string | null; alt: string }) {
    if (!url) {
        return null;
    }

    return (
        <div className="mt-2">
            <img
                src={url}
                alt={alt}
                className="h-20 w-20 rounded-md border object-cover"
            />
        </div>
    );
}
