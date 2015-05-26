#!/usr/bin/perl

use warnings;
use strict;

# Originally stolen from Ewan Birney's (Bioperl's) bp_translate_seq.pl

use Bio::SeqIO;
use Getopt::Long;


my $format = 'fasta';
my $oformat = 'fasta';
my ($outfile, $infile, $nframes, $minlength);
my $myout;
GetOptions(
    "infile=s"	=> \$infile,
    "outfile=s"   => \$outfile,
    "frames|nframes=i"	=> \$nframes,
    "minlength=i"  => \$minlength,
#	   'format:s'  => \$format,
);
die "Must get -in blah.fasta -out out.fasta\n"
unless defined($outfile) && defined($infile);
die "-frames must be 3 or 6\n"
   unless defined($nframes) && ($nframes == 3 || $nframes == 6);
die "-minlength must be defined\n"
   unless defined($minlength);

my $Min_Peptide_Length = $minlength;
#my $Min_Peptide_Length = 15; # min length in aa to bother printing

my $seqin = Bio::SeqIO->new( -format => $format, -file => $infile); 
my $seqout = Bio::SeqIO->new( -format => $oformat, -file => ">$outfile" );

my $total_input_seqs = 0;
my $total_output_seqs = 0;
while( (my $seq = $seqin->next_seq()) ) {
  if (length($seq->seq) >= 1) {
    $total_input_seqs++;
    my $id = $seq->id;
    my $revseq = $seq->revcom;
    my @full_translations = ();
    foreach my $frame (0, 1, 2) {
	my @peptides = get_peptides($seq, $frame, !"is_reversed");
	push @peptides, get_peptides($revseq, $frame, "is_reversed") if $nframes == 6;
	$total_output_seqs += @peptides;
	$seqout->write_seq($_) for @peptides;
    }
  }
}

warn "Translated $total_input_seqs seqs from $infile in $nframes frames
\t-> $total_output_seqs peptides in $outfile\n";
exit;

sub get_peptides {
    my ($seq, $frame, $is_reversed) = @_;
    my $id = $seq->id;
    my $gi = '';
    if ($id =~ /gi\|(\d+)\|/) {
      $gi = $1;
    } else {
      $id =~ /^\s*(\S+)\s*/ or die "Unrecognized sequence header format: '$id' \n";
      $gi = $1;
    }
    #$id =~ /gi\|(\d+)\|/ or die "Unexpected id '$id' doesn't have gi||\n";
    #my $gi = $1;
    my $desc = $seq->description;
    my $pseq = $seq->translate(-frame=>$frame);
    my $full_translation = $pseq->seq;
    my $piece_count = 0;
    my @peptides; # return seqs
    my ($start_pos, $end_pos) = (0, 0);
    while ($full_translation =~ /([A-Za-z]+)(\*|$)/g) {
	$start_pos = $-[1] + 1; # 0-indexed
	$end_pos = $+[1]; # 0-indexed, but it's position AFTER end of match
	my $peptide = $1;
	if (length($peptide) >= $Min_Peptide_Length) {
	    my $piece_count = @peptides + 1;
	    my $subid = $gi . ($is_reversed ? "r" : "f") . "$frame.$piece_count";
	    my $framedesc = $is_reversed ? -$frame : $frame;
	    $framedesc = -3 if $is_reversed && $framedesc == 0;
	    my $subdesc = "$desc translated in frame $framedesc, piece $piece_count (aa $start_pos-$end_pos)";
	    my $subseq = Bio::Seq->new(
		-display_id	=> $subid,
		-description	=> $subdesc,
		-seq	    	=> $peptide,
	    );
	    push @peptides, $subseq;

#print ">$subid $subdesc\n$peptide\n";
#print "Length peptide = ", length($peptide), "\n";
	}
#	my $subseq = $pseq->trunc(1, 30);
#	$seqout_outframe->write_seq($subseq);

    }
    return @peptides;
}

__END__
