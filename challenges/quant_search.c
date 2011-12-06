#include <stdio.h>
#include <malloc.h>
#include <stdlib.h>

int** list;
int Height = 10;
int Length = 10;
int Increment = 5;
int search_num = -1;

int i, j;

void search(int** matrix, int val);

int main(int argc, char** argv)
{
  if (argc < 3)
  {
    printf("./foo srand_val search_num\n");
    exit(1);
  }

  srand(atoi(argv[1]));
  search_num = atoi(argv[2]);

  list = malloc(sizeof(int*)*Length);
  for (i = 0; i < Length; i++)
  {
    list[i] = malloc(sizeof(int)*Height);

    j = 0;
    do 
    {
      list[i][j] = rand() % Increment;
      if (j > 0 && list[i][j-1] > list[i][j])
        list[i][j] = list[i][j-1] + list[i][j];
      if (i > 0 && list[i-1][j] > list[i][j])
        list[i][j] = list[i][j] + list[i-1][j];
    } while (++j < Height);
  }

  for (i = 0; i < Length; i++)
  {
    for (j = 0; j < Height; j++)
      printf("%d,",list[i][j]);
    printf("\n");
  }

  search(list,search_num);
}

void search(int** matrix, int val)
{
  searchPart(matrix, val, 0,0, Length-1,Height-1);
}

int searchPart(int** matrix, int val, int trx, int try, int blx, int bly)
{
  if (trx > Length) return -1;
  if (try > Height) return -1;
  if (blx < 0) return -1;
  if (bly < 0) return -1;
  if (bly < try) return -1;
  if (blx < trx) return -1;

  if (matrix[trx][try] < val && matrix[trx][bly] < val)
    searchPart(matrix,val,trx+1,try,blx,bly);
  else if (matrix[trx][try] < val && matrix[blx][try] < val)
    searchPart(matrix, val, trx, try+1, blx, bly);
  else if (matrix[trx][bly] > val && matrix[blx][bly] > val)
    searchPart(matrix,val,trx,try,blx,bly-1);
  else if (matrix[blx][bly] > val && matrix[blx][try] > val)
    searchPart(matrix,val,trx,try,blx-1,bly);
  else if (matrix[blx][bly] == val || matrix[trx][try] == val || 
    matrix[blx][try] == val || matrix[trx][bly] == val)
  {
    printf("found %d in %d-%d,%d-%d\n", val, trx,try,blx,bly);
    return 1;
  }
  else 
    return 0;
}
