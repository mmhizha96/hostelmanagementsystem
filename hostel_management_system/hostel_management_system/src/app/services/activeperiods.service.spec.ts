import { TestBed } from '@angular/core/testing';

import { ActiveperiodsService } from './activeperiods.service';

describe('ActiveperiodsService', () => {
  let service: ActiveperiodsService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(ActiveperiodsService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
