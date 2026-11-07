# Maintenance notes

The folder cleanup removed generated logs, debug toolbar captures, a stale session,
previous test reports, empty placeholders, unreferenced root controller/view
snapshots, and standalone troubleshooting scripts. The public session debugger,
cache-reset script, and admin test page were also removed.

Application and framework code, PHPUnit tests, the database snapshot, inactive migrations,
uploaded images and the separate API source were retained.
The server startup marker was retained because authentication uses it.

`history/` contains earlier implementation and troubleshooting notes. These are
historical records: commands may refer to scripts removed by the cleanup, and
source paths in these notes are relative to the project root. The standalone
deployment scripts and root email deployment guides have been removed.

## Recovery

The exact removed files, moved documents, and recovery archive location are listed
in [cleanup-manifest.json](cleanup-manifest.json). The archive contains all removed
files and the original README, ignore rules, and moved notes. It is stored outside
the project in the local temporary directory and may be removed by the operating
system. Copy it elsewhere if you need long-term retention.

To recover an individual file, run this from the project root, substituting the
archive path from the manifest and the desired relative file path:

```sh
tar -xzf /tmp/lost-and-found-cleanup-TIMESTAMP.tar.gz path/to/file
```

Runtime logs, cache, debug toolbar output, session files, and build reports are
ignored by Git. Directory placeholders and access-control files are preserved.

The subsequent script cleanup is recorded in [script-removal-manifest.json](script-removal-manifest.json), with a separate recovery archive.
