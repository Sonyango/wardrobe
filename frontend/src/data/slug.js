export function slug(s = '') {
    return String(s).trim().replace(/\s+/g, '-').toLowerCase();
}